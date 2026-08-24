<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\File;

class CreateSizeBasedLogger
{
    private const MAX_BYTES = 500 * 1024 * 1024; // 500 MB

    public function __invoke(array $config)
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $logsDir   = storage_path('logs');
        $pointer   = storage_path('Logpath.txt');
        $level     = $this->levelFromConfig($config['level'] ?? 'debug');

        // 1) Ensure directories/files exist
        if (!File::exists($logsDir)) {
            File::makeDirectory($logsDir, 0755, true);
        }
        if (!File::exists($pointer)) {
            File::put($pointer, '');
        }

        // 2) Read current file path (may be empty)
        $currentPath = trim((string) File::get($pointer));

        // 3) If pointer empty or file missing => start new file with next number
        if ($currentPath === '' || !File::exists($currentPath)) {
            $nextNum = $this->nextFileNumber($logsDir);
            $newPath = $this->buildFilePath($logsDir, $nextNum, $timestamp);
            File::put($pointer, $newPath);
            return $this->makeLogger($newPath, $level);
        }

        // 4) Rotate if too big
        $size = File::size($currentPath);
        if ($size !== false && $size > self::MAX_BYTES) {
            $num = $this->extractFileNumber($currentPath) ?? $this->nextFileNumber($logsDir);
            $newPath = $this->buildFilePath($logsDir, $num + 1, $timestamp);
            File::put($pointer, $newPath);
            return $this->makeLogger($newPath, $level);
        }

        // 5) Keep writing to current
        return $this->makeLogger($currentPath, $level);
    }

    private function nextFileNumber(string $logsDir): int
    {
        $max = 0;
        foreach (File::files($logsDir) as $file) {
            $name = $file->getFilename();
            if (preg_match('/^digibima_(\d+)_.*\.log$/', $name, $m)) {
                $n = (int) $m[1];
                if ($n > $max) $max = $n;
            }
        }
        return $max + 1;
    }

    private function extractFileNumber(string $fullPath): ?int
    {
        $base = basename($fullPath);
        return preg_match('/^digibima_(\d+)_.*\.log$/', $base, $m) ? (int) $m[1] : null;
    }

    private function buildFilePath(string $logsDir, int $n, string $timestamp): string
    {
        return $logsDir . DIRECTORY_SEPARATOR . "digibima_{$n}_{$timestamp}.log";
    }

    private function makeLogger(string $path, int $level): Logger
    {
        $log = new Logger('size_based');
        $log->pushHandler(new StreamHandler($path, $level));
        return $log;
    }

    private function levelFromConfig(string $level): int
    {
        // Map Laravel levels to Monolog
        return match (strtolower($level)) {
            'debug' => Logger::DEBUG,
            'info' => Logger::INFO,
            'notice' => Logger::NOTICE,
            'warning' => Logger::WARNING,
            'error' => Logger::ERROR,
            'critical' => Logger::CRITICAL,
            'alert' => Logger::ALERT,
            'emergency' => Logger::EMERGENCY,
            default => Logger::DEBUG,
        };
    }
}

<?php

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

Route::view('tinker', 'developer.terminal');

Route::post('/run-tinker', function (Request $request) {
    $commands = $request->input('commands', []);

    if (!is_array($commands) || empty($commands)) {
        return response()->json(['error' => 'No commands received'], 400);
    }

    $output = [];

    foreach ($commands as $command) {
        $command = trim($command);

        // If command is a "use" statement, execute separately
        if (str_starts_with($command, 'use ')) {
            $process = new Process(['php', 'artisan', 'tinker', '--execute=' . escapeshellarg($command)]);
            $process->run();
            continue; // Skip output for "use" statements
        }

        // Run actual commands and capture output
        $process = new Process(['php', 'artisan', 'tinker', '--execute=' . escapeshellarg('dump(' . $command . ');')]);
        $process->run();

        if (!$process->isSuccessful()) {
            $output[] = "Error: " . trim($process->getErrorOutput());
        } else {
            $result = trim($process->getOutput());
            if (!empty($result)) {
                $output[] = $result;
            }
        }
    }

    return response()->json(['output' => implode("\n", $output)]);
});
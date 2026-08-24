<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Mycommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mycommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info(['Response_Command'=>"Running Every Minute"]);
    }
}

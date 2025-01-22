<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear';
    protected $description = 'Clear the Laravel log file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');

        if (file_exists($logPath)) {
            file_put_contents($logPath, '');
            $this->info('Logs have been cleared!');
        } else {
            $this->warn('Log file does not exist.');
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class PreventGuestBeforeLaunch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:launch {action}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Control guest access before official launch (enable/disable)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        if ($action === 'enable') {
            Cache::forever('app_pre_launch', true);
            $this->info('Pre-launch mode enabled. Guests cannot access the app.');
        } elseif ($action === 'disable') {
            Cache::forget('app_pre_launch');
            $this->info('Pre-launch mode disabled. Guests can access the app.');
        } else {
            $this->error('Invalid action. Use "enable" or "disable".');
        }
    }
}

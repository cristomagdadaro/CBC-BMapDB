<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetRoute extends Command
{
    protected $signature = 'route:reset';

    protected $description = 'Reset routes to fix Custom Modules Routes';

    public function handle()
    {
        // Run 'composer dump-autoload' through exec
        exec('composer dump-autoload', $output, $status);

        if ($status === 0) {
            $this->info('Composer autoload dumped successfully.');
        } else {
            $this->error('Failed to run composer dump-autoload.');
        }

        // Clear application cache
        $this->call('cache:clear');

        // Clear configuration cache
        $this->call('config:clear');

        // Clear route cache
        $this->call('route:clear');

        // Clear compiled views
        $this->call('view:clear');

        $this->info('Routes and caches have been reset successfully.');
    }
}

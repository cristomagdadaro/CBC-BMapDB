<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetProjectsTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop and seed specific tables without resetting the entire database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $tablesToReset = ['users','applications','accounts','breeders','commodities','twg_expert','twg_project','twg_product','twg_service','model_has_roles','model_has_permissions']; // Specify your tables here

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tablesToReset as $table) {
            $this->info("Resetting table: $table");
            DB::table($table)->truncate(); // Truncate the table
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Call the seeders for specific tables
        $this->call('db:seed', ['--class' => 'ApplicationSeeder']);
        $this->call('db:seed', ['--class' => 'UserSeeder']);
        $this->call('db:seed', ['--class' => 'BreedersMapSeeder']);
        $this->call('db:seed', ['--class' => 'TWGDatabaseSeeder']);


        $this->info('Specific tables reset and seeded successfully.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        $tablesToReset = ['users','applications','accounts','breeders','commodities','commodity_characteristics','commodity_info','twg_expert','twg_project','twg_product','twg_service','model_has_roles','model_has_permissions']; // Specify your tables here

        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        foreach ($tablesToReset as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            $this->info("Resetting table: $table");
            if ($driver === 'sqlite') {
                DB::table($table)->delete();
            } else {
                DB::table($table)->truncate(); // Truncate the table
            }
        }

        // Re-enable foreign key checks
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Call the seeders for specific tables
        $this->call('db:seed', ['--class' => 'ApplicationSeeder']);
        $this->call('db:seed', ['--class' => 'UserSeeder']);
        $this->call('db:seed', ['--class' => 'BreedersMapSeeder']);
        $this->call('db:seed', ['--class' => 'TWGDatabaseSeeder']);


        $this->info('Specific tables reset and seeded successfully.');
    }
}

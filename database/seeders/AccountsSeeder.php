<?php

namespace Database\Seeders;

use App\Modules\Administrator\Models\Accounts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Accounts::factory()->count(5)->create();
    }
}

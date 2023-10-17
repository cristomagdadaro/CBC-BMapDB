<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function refreshDatabase()
    {
        $this->beforeRefreshingDatabase();
        $this->refreshTestDatabase();

        $this->dropTables();

        $this->afterRefreshingDatabase();
    }

    public function dropTables()
    {
        $this->artisan('migrate:fresh');
    }

    public function afterRefreshingDatabase()
    {
        $this->artisan('db:seed');
    }

}

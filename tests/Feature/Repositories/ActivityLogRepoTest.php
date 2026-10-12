<?php

namespace Tests\Feature\Repositories;

use App\Models\ApiRequestLog;
use App\Models\User;
use App\Repository\API\ActivityLogRepo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogRepoTest extends TestCase
{
    use RefreshDatabase;

    protected ActivityLogRepo $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(ActivityLogRepo::class);
    }

    public function test_get_logs_applies_mine_filter_by_default_for_non_admins()
    {
        $user = User::factory()->create();

        ApiRequestLog::create([
            'user_id' => $user->id,
            'method' => 'POST',
            'model' => 'App\Models\TestModel',
            'url' => '/api/test',
        ]);

        // Another user's log
        ApiRequestLog::create([
            'user_id' => User::factory()->create()->id,
            'method' => 'POST',
            'model' => 'App\Models\OtherModel',
            'url' => '/api/other',
        ]);

        $filters = []; // Default mine filter is true
        $logs = $this->repo->getLogs($filters, $user);

        $this->assertEquals(1, $logs->total());
        $this->assertEquals($user->id, $logs->first()->user_id);
    }

    public function test_get_logs_can_filter_by_user_id_for_admins()
    {
        $admin = User::factory()->create();
        $admin->assignRole(\App\Enums\Role::ADMIN->value);

        $targetUser = User::factory()->create();

        ApiRequestLog::create([
            'user_id' => $targetUser->id,
            'method' => 'POST',
            'model' => 'App\Models\TestModel',
            'url' => '/api/test',
        ]);

        ApiRequestLog::create([
            'user_id' => $admin->id,
            'method' => 'PUT',
            'model' => 'App\Models\AdminModel',
            'url' => '/api/admin',
        ]);

        $filters = ['mine' => false, 'user_id' => $targetUser->id];
        $logs = $this->repo->getLogs($filters, $admin);

        $this->assertEquals(1, $logs->total());
        $this->assertEquals($targetUser->id, $logs->first()->user_id);
    }

    public function test_get_logs_filters_out_get_head_options_methods()
    {
        $user = User::factory()->create();

        $methods = ['GET', 'HEAD', 'OPTIONS', 'POST', 'PUT', 'DELETE'];

        foreach ($methods as $method) {
            ApiRequestLog::create([
                'user_id' => $user->id,
                'method' => $method,
                'model' => 'App\Models\TestModel',
                'url' => '/api/test',
            ]);
        }

        $filters = ['mine' => true];
        $logs = $this->repo->getLogs($filters, $user);

        // Should only return POST, PUT, DELETE
        $this->assertEquals(3, $logs->total());
        
        $returnedMethods = $logs->pluck('method')->toArray();
        $this->assertNotContains('GET', $returnedMethods);
        $this->assertNotContains('HEAD', $returnedMethods);
        $this->assertNotContains('OPTIONS', $returnedMethods);
    }

    public function test_get_logs_can_filter_by_model_and_method()
    {
        $user = User::factory()->create();

        ApiRequestLog::create([
            'user_id' => $user->id,
            'method' => 'POST',
            'model' => 'App\Models\TargetModel',
            'url' => '/api/target',
        ]);

        ApiRequestLog::create([
            'user_id' => $user->id,
            'method' => 'PUT',
            'model' => 'App\Models\TargetModel',
            'url' => '/api/target',
        ]);

        ApiRequestLog::create([
            'user_id' => $user->id,
            'method' => 'POST',
            'model' => 'App\Models\OtherModel',
            'url' => '/api/other',
        ]);

        $filters = [
            'mine' => true,
            'method' => 'post', // should handle case insensitivity via strtoupper
            'model' => 'App\Models\TargetModel'
        ];

        $logs = $this->repo->getLogs($filters, $user);

        $this->assertEquals(1, $logs->total());
        $this->assertEquals('POST', $logs->first()->method);
        $this->assertEquals('App\Models\TargetModel', $logs->first()->model);
    }
}

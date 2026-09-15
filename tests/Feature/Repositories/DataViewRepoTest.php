<?php

namespace Tests\Feature\Repositories;

use App\Models\DataView;
use App\Models\User;
use App\Repository\API\DataViewRepo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Modules\PbMap\Models\Commodity;
use Illuminate\Support\Str;

class DataViewRepoTest extends TestCase
{
    use RefreshDatabase;

    protected DataViewRepo $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(DataViewRepo::class);
    }

    public function test_get_data_views_for_table_as_admin()
    {
        $admin = User::factory()->create();
        $admin->assignRole(\App\Enums\Role::ADMIN->value);

        $table = (new Commodity)->getTable();

        // Create dataviews for different users
        DataView::create([
            'uuid' => Str::uuid(),
            'user_account_id' => $admin->id,
            'model' => $table,
            'visibility_guard' => 'public',
            'columns' => ['id', 'name'],
        ]);

        $user2 = User::factory()->create();
        DataView::create([
            'uuid' => Str::uuid(),
            'user_account_id' => $user2->id,
            'model' => $table,
            'visibility_guard' => 'private',
            'columns' => ['id', 'name', 'secret'],
        ]);

        // Admin should see both data views
        $views = $this->repo->getDataViewsForTable($table, $admin);

        $this->assertCount(2, $views);
        $this->assertArrayHasKey('public', $views);
        $this->assertArrayHasKey('private', $views);
    }

    public function test_get_data_views_for_table_as_regular_user()
    {
        $table = (new Commodity)->getTable();
        $user = User::factory()->create();

        DataView::create([
            'uuid' => Str::uuid(),
            'user_account_id' => $user->id,
            'model' => $table,
            'visibility_guard' => 'public',
            'columns' => ['id', 'name'],
        ]);

        // Another user's data view
        DataView::create([
            'uuid' => Str::uuid(),
            'user_account_id' => User::factory()->create()->id,
            'model' => $table,
            'visibility_guard' => 'private',
            'columns' => ['id', 'name', 'secret'],
        ]);

        $views = $this->repo->getDataViewsForTable($table, $user);

        // Regular user should only see their own dataview
        $this->assertCount(1, $views);
        $this->assertArrayHasKey('public', $views);
    }

    public function test_get_grouped_data_views()
    {
        $table = (new Commodity)->getTable();
        $user = User::factory()->create();

        DataView::create([
            'uuid' => Str::uuid(),
            'user_account_id' => $user->id,
            'model' => $table,
            'visibility_guard' => 'public',
            'columns' => ['id', 'name'],
        ]);

        $grouped = $this->repo->getGroupedDataViews($table);

        $this->assertIsArray($grouped);
        $this->assertArrayHasKey($user->id, $grouped);
        $this->assertArrayHasKey($table, $grouped[$user->id]);
        $this->assertArrayHasKey('public', $grouped[$user->id][$table]);
        $this->assertEquals(['id', 'name'], $grouped[$user->id][$table]['public']);
    }

    public function test_update_by_uuid()
    {
        $uuid = Str::uuid()->toString();
        DataView::create([
            'uuid' => $uuid,
            'user_account_id' => User::factory()->create()->id,
            'model' => 'some_table',
            'visibility_guard' => 'public',
            'columns' => ['id'],
        ]);

        $updatedView = $this->repo->updateByUuid($uuid, [
            'columns' => ['id', 'new_column']
        ]);

        $this->assertEquals(['id', 'new_column'], $updatedView->columns);
        $this->assertDatabaseHas('data_views', [
            'uuid' => $uuid,
        ]);
    }
}

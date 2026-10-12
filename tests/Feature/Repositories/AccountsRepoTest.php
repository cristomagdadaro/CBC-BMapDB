<?php

namespace Tests\Feature\Repositories;

use App\Enums\Role as RoleEnum;
use App\Models\User;
use App\Repository\API\AccountsRepo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AccountsRepoTest extends TestCase
{
    use RefreshDatabase;

    protected AccountsRepo $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(AccountsRepo::class);
    }

    public function test_assign_role_for_user()
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => RoleEnum::BREEDER->value, 'guard_name' => 'web']);

        $this->repo->assignRoleForUser($user, $role->id);

        $this->assertTrue($user->hasRole(RoleEnum::BREEDER->value));
    }

    public function test_update_user_permissions_and_roles_when_not_approved()
    {
        $user = User::factory()->create();
        $role = Role::firstOrCreate(['name' => RoleEnum::RESEARCHER->value, 'guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'test_permission', 'guard_name' => 'web']);
        
        $user->assignRole($role);
        $user->givePermissionTo($permission);

        $this->assertTrue($user->hasRole(RoleEnum::RESEARCHER->value));
        $this->assertTrue($user->hasPermissionTo('test_permission'));

        // Action: Update with null approved_at should revoke all
        $this->repo->updateUserPermissionsAndRoles($user->id, [
            'approved_at' => null,
            'permissions' => [$permission->id],
            'role' => [$role->id]
        ]);

        $user->refresh();
        $this->assertFalse($user->hasRole(RoleEnum::RESEARCHER->value));
        $this->assertFalse($user->hasPermissionTo('test_permission'));
    }

    public function test_update_user_permissions_and_roles_when_approved()
    {
        $user = User::factory()->create();
        
        // Setup initial role and permission
        $oldRole = Role::firstOrCreate(['name' => 'old_role', 'guard_name' => 'web']);
        $newRole = Role::firstOrCreate(['name' => 'new_role', 'guard_name' => 'web']);
        
        $oldPermission = Permission::firstOrCreate(['name' => 'old_permission', 'guard_name' => 'web']);
        $newPermission = Permission::firstOrCreate(['name' => 'new_permission', 'guard_name' => 'web']);

        $user->assignRole($oldRole);
        $user->givePermissionTo($oldPermission);

        // Action: Revoke old (negative ID) and assign new (positive ID)
        $this->repo->updateUserPermissionsAndRoles($user->id, [
            'approved_at' => now(),
            'permissions' => [-$oldPermission->id, $newPermission->id],
            'role' => [-$oldRole->id, $newRole->id]
        ]);

        $user->refresh();

        // Check Roles
        $this->assertFalse($user->hasRole('old_role'));
        $this->assertTrue($user->hasRole('new_role'));

        // Check Permissions
        $this->assertFalse($user->hasPermissionTo('old_permission'));
        $this->assertTrue($user->hasPermissionTo('new_permission'));
    }
}

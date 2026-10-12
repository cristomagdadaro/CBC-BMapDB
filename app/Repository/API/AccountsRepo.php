<?php

namespace App\Repository\API;

use App\Models\Accounts;
use App\Repository\AbstractRepoService;

class AccountsRepo extends AbstractRepoService
{
    public function __construct(
        Accounts $model,
        protected UserRepo $userRepo,
        protected RoleRepo $roleRepo,
        protected PermissionRepo $permissionRepo
    ) {
        parent::__construct($model);
    }

    public function deleteByUserId(int $userId): void
    {
        $this->model->where('user_id', $userId)->delete();
    }

    public function assignRoleForUser($user, ?int $roleId): void
    {
        if ($roleId) {
            $roleName = $this->roleRepo->getRoleNameById($roleId);
            if (!empty($roleName)) {
                $user->assignRole($roleName);
            }
        }
    }

    public function updateUserPermissionsAndRoles(int $userId, array $validatedData): void
    {
        $user = $this->userRepo->findUserById($userId);
        if (!$user) {
            return;
        }

        $approvedAt = $validatedData['approved_at'] ?? null;
        $permissionIds = $validatedData['permissions'] ?? [];
        $roles = $validatedData['role'] ?? [];

        if (!$approvedAt) {
            $user->revokePermissionTo($user->permissions);
            $user->roles()->detach();
            return;
        }

        // Handle permissions
        $negativePermissionIds = array_filter($permissionIds, fn($id) => $id < 0);
        $positivePermissionIds = array_filter($permissionIds, fn($id) => $id > 0);

        $validPermissionIds = $this->permissionRepo->getValidPermissionIdsByIds(
            array_map('abs', $permissionIds)
        );

        if (count($validPermissionIds) !== count(array_map('abs', $permissionIds))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'permissions' => ['Some of the permission IDs do not exist in the permissions table.']
            ]);
        }

        if (!empty($negativePermissionIds)) {
            $permissionsToRevoke = $this->permissionRepo->getPermissionNamesByIds(
                array_map('abs', $negativePermissionIds)
            );
            $user->revokePermissionTo($permissionsToRevoke);
        }

        if (!empty($positivePermissionIds)) {
            $permissionsToAssign = $this->permissionRepo->getPermissionNamesByIds($positivePermissionIds);
            $user->givePermissionTo($permissionsToAssign);
        }

        // Handle roles
        $negativeRoles = array_filter($roles, fn($id) => $id < 0);
        $positiveRoles = array_filter($roles, fn($id) => $id > 0);

        if (!empty($negativeRoles)) {
            $rolesToDetach = array_map('abs', $negativeRoles);
            $validRoleIds = $this->roleRepo->getValidRoleIdsByIds($rolesToDetach);
            $user->roles()->detach($validRoleIds);
        }

        if (!empty($positiveRoles)) {
            foreach ($positiveRoles as $roleId) {
                $user->assignRole($roleId);
            }
        }
    }
}

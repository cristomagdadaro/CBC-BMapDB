<?php

namespace App\Http\Controllers\API;

use Modules\PbMap\Enums as PbMapPermissions;
use Modules\TwgDb\Enums as TwgDbPermissions;
use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\GetAccountForRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Repository\API\AccountsRepo;
use App\Repository\API\PermissionRepo;
use App\Repository\API\RoleRepo;
use App\Repository\API\UserRepo;
use Illuminate\Validation\ValidationException;

class AccountController extends BaseController
{
    protected RoleRepo $roleRepo;
    protected UserRepo $userRepo;
    protected PermissionRepo $permissionRepo;

    public function __construct(
        AccountsRepo $accountRepository,
        RoleRepo $roleRepo,
        UserRepo $userRepo,
        PermissionRepo $permissionRepo
    )
    {
        $this->service = $accountRepository;
        $this->roleRepo = $roleRepo;
        $this->userRepo = $userRepo;
        $this->permissionRepo = $permissionRepo;
    }

    public function index(GetAccountForRequest $request)
    {
        return parent::_index($request);
    }

    public function show(GetAccountForRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }

    public function store(CreateAccountRequest $request)
    {
        $roleName = $this->roleRepo->getRoleNameById((int) $request->validated()['role']);
        if (!empty($roleName))
        {
            auth()->user()->assignRole($roleName);
            return parent::_store($request);
        }
        return $this->sendResponse(['request' => $request->toArray()]);
    }

    /**
     * @throws ValidationException
     */
    public function update(UpdateAccountRequest $request, $id)
    {
        // Validate and update the account
        $validatedData = $request->validated();
        $data = $this->service->update($id, $validatedData);

        // Retrieve the updated user and app
        $user = $this->userRepo->findUserById($validatedData['user_id']);
        $appId = $validatedData['app_id'] ?? null;
        $approvedAt = $validatedData['approved_at'] ?? null; // Get the approved_at value
        $permissionIds = $validatedData['permissions'] ?? [];
        $roles = $validatedData['role'] ?? [];

        if ($user) {
            // If approved_at is null, revoke all permissions and roles
            if (!$approvedAt) {
                $user->revokePermissionTo($user->permissions); // revoke all user's permissions
                $user->roles()->detach(); // remove all roles
            } else {
                // Handle permissions
                // Extract negative values from $permissionIds
                $negativePermissionIds = array_filter($permissionIds, fn($id) => $id < 0);

                // Extract positive values from $permissionIds
                $positivePermissionIds = array_filter($permissionIds, fn($id) => $id > 0);

                $validPermissionIds = $this->permissionRepo->getValidPermissionIdsByIds(
                    array_map('abs', $permissionIds)
                );

                if (count($validPermissionIds) !== count(array_map('abs', $permissionIds))) {
                    // Throw a validation exception if there are invalid permission IDs
                    throw ValidationException::withMessages([
                        'permissions' => ['Some of the permission IDs do not exist in the permissions table.']
                    ]);
                }

                // Revoke permissions specified as negative in request
                if (!empty($negativePermissionIds)) {
                    $permissionsToRevoke = $this->permissionRepo->getPermissionNamesByIds(
                        array_map('abs', $negativePermissionIds)
                    );

                    $user->revokePermissionTo($permissionsToRevoke);
                }

                // Assign new permissions
                if (!empty($positivePermissionIds)) {
                    $permissionsToAssign = $this->getPermissionsFromRequest($positivePermissionIds);
                    $user->givePermissionTo($permissionsToAssign);
                }

                // Handle roles
                $negativeRoles = array_filter($roles, fn($id) => $id < 0);
                $positiveRoles = array_filter($roles, fn($id) => $id > 0);

                if (!empty($negativeRoles)) {
                    $rolesToDetach = array_map('abs', $negativeRoles);

                    // Filter invalid role IDs before detaching them
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

        return $data;
    }

    /**
     * Get permissions from the permissions request.
     *
     * @param Request $request
     * @return array
    */
    protected function getPermissionsFromRequest($ids): array
    {
        // The permissions request should be an array of permission IDs
        // e.g. ['1', '2', '3']
        // get the permissions name in the permission table
        return $this->permissionRepo->getPermissionNamesByIds($ids);

    }

    /**
     * Get permissions based on the app ID.
     *
     * @param int $appId
     * @return array
     */
    protected function getPermissionsForApp(int $appId): array
    {
        // Define permissions based on app ID
        // You might want to have a mapping or logic to determine permissions for each app
        return match ($appId) {
            1 => [
                TwgDbPermissions\Permissions::CREATE_TWG_EXPERT->value,
                TwgDbPermissions\Permissions::UPDATE_TWG_EXPERT->value,
                TwgDbPermissions\Permissions::READ_TWG_EXPERT->value,

                TwgDbPermissions\Permissions::CREATE_TWG_SERVICE->value,
                TwgDbPermissions\Permissions::UPDATE_TWG_SERVICE->value,
                TwgDbPermissions\Permissions::READ_TWG_SERVICE->value,

                TwgDbPermissions\Permissions::CREATE_TWG_PRODUCT->value,
                TwgDbPermissions\Permissions::UPDATE_TWG_PRODUCT->value,
                TwgDbPermissions\Permissions::READ_TWG_PRODUCT->value,

                TwgDbPermissions\Permissions::CREATE_TWG_PROJECT->value,
                TwgDbPermissions\Permissions::UPDATE_TWG_PROJECT->value,
                TwgDbPermissions\Permissions::READ_TWG_PROJECT->value,
            ],
            2 => [
                PbMapPermissions\Permissions::CREATE_BREEDER->value,
                PbMapPermissions\Permissions::UPDATE_BREEDER->value,
                PbMapPermissions\Permissions::READ_BREEDER->value,

                PbMapPermissions\Permissions::CREATE_COMMODITY->value,
                PbMapPermissions\Permissions::UPDATE_COMMODITY->value,
                PbMapPermissions\Permissions::READ_COMMODITY->value,
            ],
            default => [],
        };
    }


    public function destroy($id)
    {
        return parent::_destroy($id);
    }
}

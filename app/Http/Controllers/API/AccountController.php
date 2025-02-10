<?php

namespace App\Http\Controllers\API;

use App\Enums\Permission as PermissionEnum;
use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\GetAccountForRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Role;
use App\Models\User;
use App\Repository\API\AccountsRepo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;

class AccountController extends BaseController
{

    public function __construct(AccountsRepo $accountRepository)
    {
        $this->service = $accountRepository;
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
        $role = Role::select('name')->where('id',$request->validated()['role'])->first();
        if (!empty($role))
        {
            auth()->user()->assignRole($role->name);
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
        $user = User::find($validatedData['user_id']) ?? null;
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

                $validPermissionIds = DB::table('permissions')
                    ->whereIn('id', array_map('abs', $permissionIds))
                    ->pluck('id')
                    ->toArray();

                if (count($validPermissionIds) !== count(array_map('abs', $permissionIds))) {
                    // Throw a validation exception if there are invalid permission IDs
                    throw ValidationException::withMessages([
                        'permissions' => ['Some of the permission IDs do not exist in the permissions table.']
                    ]);
                }

                // Revoke permissions specified as negative in request
                if (!empty($negativePermissionIds)) {
                    $permissionsToRevoke = DB::table('permissions')
                        ->whereIn('id', array_map('abs', $negativePermissionIds))
                        ->pluck('name')
                        ->toArray();

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
                    $validRoleIds = DB::table('roles')
                        ->whereIn('id', $rolesToDetach)
                        ->pluck('id')
                        ->toArray();

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
        return DB::table('permissions')
            ->whereIn('id', $ids)
            ->pluck('name')
            ->toArray();

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
                PermissionEnum::CREATE_TWG_EXPERT->value,
                PermissionEnum::UPDATE_TWG_EXPERT->value,
                PermissionEnum::READ_TWG_EXPERT->value,

                PermissionEnum::CREATE_TWG_SERVICE->value,
                PermissionEnum::UPDATE_TWG_SERVICE->value,
                PermissionEnum::READ_TWG_SERVICE->value,

                PermissionEnum::CREATE_TWG_PRODUCT->value,
                PermissionEnum::UPDATE_TWG_PRODUCT->value,
                PermissionEnum::READ_TWG_PRODUCT->value,

                PermissionEnum::CREATE_TWG_PROJECT->value,
                PermissionEnum::UPDATE_TWG_PROJECT->value,
                PermissionEnum::READ_TWG_PROJECT->value,
            ],
            2 => [
                PermissionEnum::CREATE_BREEDER->value,
                PermissionEnum::UPDATE_BREEDER->value,
                PermissionEnum::READ_BREEDER->value,

                PermissionEnum::CREATE_COMMODITY->value,
                PermissionEnum::UPDATE_COMMODITY->value,
                PermissionEnum::READ_COMMODITY->value,
            ],
            default => [],
        };
    }


    public function destroy($id)
    {
        return parent::_destroy($id);
    }
}

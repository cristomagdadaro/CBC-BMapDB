<?php

namespace App\Http\Controllers\API;

use App\Enums\Permission as PermissionEnum;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\GetAccountForRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountsCollection;
use App\Http\Resources\BaseCollection;
use App\Models\Accounts;
use App\Models\Permission;
use App\Models\User;
use App\Repository\API\AccountsRepo;
use Faker\Core\Uuid;
use Illuminate\Support\Collection;
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
        $this->service->appendWith(['user', 'application']);
        $data = $this->service->search(new Collection($request->validated()));
        return new BaseCollection($data);
    }

    public function show($id)
    {
        $this->service->appendWith(['user','application']);
        return $this->service->find($id);
    }

    public function store(CreateAccountRequest $request)
    {
        return $this->service->create($request->validated());
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
        $user = User::find($validatedData['user_id']);
        $appId = $validatedData['app_id'];
        $approvedAt = $validatedData['approved_at']; // Get the approved_at value
        $permissionIds = $validatedData['permissions'] ?? [];

        // Validate that all permission IDs exist in the permissions table
        $validPermissionIds = DB::table('permissions')
            ->whereIn('id', $permissionIds)
            ->pluck('id')
            ->toArray();

        if (count($validPermissionIds) !== count($permissionIds)) {
            // Throw a validation exception if there are invalid permission IDs
            throw ValidationException::withMessages([
                'permissions' => ['Some of the permission IDs do not exist in the permissions table.']
            ]);
        }

        // Get the permissions based on the app ID
        $validPermissionIds = $this->getPermissionsFromRequest($request->get('permissions', []));

        if ($user) {
            // If approved_at has a value, assign the permissions
            if ($approvedAt) {
                $user->givePermissionTo($validPermissionIds);

                // Assign a role to the user with the role ID
                $role = $validatedData['role'];
                if ($role) {
                    // remove all roles from the user
                    $user->roles()->detach();
                    // assign the new role to the user
                    $user->assignRole($role);
                }
            } else {
                // If approved_at is null, remove the permissions
                $user->revokePermissionTo(Permission::all());
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
        return $this->service->delete($id);
    }
}

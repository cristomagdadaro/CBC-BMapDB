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
use App\Models\User;
use App\Repository\API\AccountsRepo;
use Faker\Core\Uuid;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

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
        return $this->service->find($id);
    }

    public function store(CreateAccountRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(UpdateAccountRequest $request, $id)
    {
        // Validate and update the account
        $validatedData = $request->validated();
        $data = $this->service->update($id, $validatedData);

        // Retrieve the updated user and app
        $user = User::find($validatedData['user_id']);
        $appId = $validatedData['app_id'];
        $approvedAt = $validatedData['approved_at']; // Get the approved_at value

        if ($user) {
            // Retrieve app-specific permissions
            $permissions = $this->getPermissionsForApp($appId);

            if ($approvedAt) {
                // If approved_at has a value, assign the permissions
                $user->givePermissionTo($permissions);
            } else {
                // If approved_at is null, remove the permissions
                $user->revokePermissionTo($permissions);
            }
        }

        return $data;
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

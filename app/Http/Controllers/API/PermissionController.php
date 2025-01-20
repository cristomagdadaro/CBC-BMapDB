<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\DeletePermissionRequest;
use App\Http\Requests\GetPermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\PermissionCollection;
use App\Models\Permission;
use App\Models\Role;
use App\Repository\API\PermissionRepo;
use App\Repository\ErrorRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class PermissionController extends BaseController
{
    public function __construct(PermissionRepo $permissionRepository)
    {
        $this->service = $permissionRepository;
    }

    /*public function index(GetPermissionRequest $request)
    {
        $data = $this->service->search(new Collection($request->validated()),false);
        return new BaseCollection($data);
    }*/

    public function index()
    {
        $permissions = Permission::all();

        $permissions = $permissions->groupBy(function ($permission) {
            if (str_contains($permission->name, 'create')) {
                return 'create';
            } elseif (str_contains($permission->name, 'read')) {
                return 'read';
            } elseif (str_contains($permission->name, 'delete')) {
                return 'delete';
            } elseif (str_contains($permission->name, 'update')) {
                return 'update';
            }
            return 'other'; // Group for permissions that don't match any of the above
        });

        return new BaseCollection($permissions);
    }


    public function show($id)
    {
        return $this->service->find($id);
    }

    public function store(CreatePermissionRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(UpdatePermissionRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public static function getPermissions()
    {
        $permissionsToCheck = [
            'create' => 'create-breeder',
            'update' => 'update-breeder',
            'delete' => 'delete-breeder',
            'view' => 'read-breeder',
          /*  'createCommodity' => 'create-commodity',
            'editCommodity' => 'update-commodity',
            'deleteCommodity' => 'delete-commodity',
            'viewCommodity' => 'read-commodity',*/
        ];

        return PermissionController::checkPermissions($permissionsToCheck);
    }

    private static function checkPermissions(array $permissions): array
    {
        $permissionChecks = [];

        foreach ($permissions as $key => $permission) {
            $permissionChecks[$key] = Gate::allows($permission, Permission::class);
        }

        return $permissionChecks;
    }
}

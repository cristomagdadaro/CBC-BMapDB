<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\BaseCollection;
use App\Models\Permission;
use App\Repository\API\PermissionRepo;
use Illuminate\Support\Facades\Gate;

class PermissionController extends BaseController
{
    public function __construct(PermissionRepo $permissionRepository)
    {
        $this->service = $permissionRepository;
    }

    public function index()
    {
        $permissions = Permission::all();

        $permissions = $permissions->groupBy(function ($permission) {
            return match (true) {
                str_contains($permission->name, 'create') => 'create',
                str_contains($permission->name, 'read') => 'read',
                str_contains($permission->name, 'delete') => 'delete',
                str_contains($permission->name, 'update') => 'update',
                default => 'other',
            };
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

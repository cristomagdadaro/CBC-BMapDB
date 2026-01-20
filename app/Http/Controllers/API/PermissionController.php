<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\GetPermissionRequest;
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
        /** @var PermissionRepo $permissionRepo */
        $permissionRepo = $this->service;

        return new BaseCollection($permissionRepo->getGroupedPermissions());
    }


    public function show(GetPermissionRequest $request, int $id)
    {
        return parent::_show($request, $id);
    }

    public function store(CreatePermissionRequest $request)
    {
        return parent::_store($request);
    }

    public function update(UpdatePermissionRequest $request, int $id)
    {
        return parent::_update($request, $id);
    }

    public function destroy(int $id)
    {
        return parent::_destroy($id);
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

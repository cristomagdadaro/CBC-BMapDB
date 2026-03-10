<?php

namespace App\Repository\API;

use App\Models\Permission;
use App\Repository\AbstractRepoService;

class PermissionRepo extends AbstractRepoService
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function getGroupedPermissions()
    {
        return $this->model->newQuery()->get()->groupBy(function ($permission) {
            if (str_contains($permission->name, 'create')) {
                return 'create';
            }
            if (str_contains($permission->name, 'read')) {
                return 'read';
            }
            if (str_contains($permission->name, 'delete')) {
                return 'delete';
            }
            if (str_contains($permission->name, 'update')) {
                return 'update';
            }
            return 'other';
        });
    }

    public function getValidPermissionIdsByIds(array $ids): array
    {
        return $this->model->whereIn('id', $ids)->pluck('id')->toArray();
    }

    public function getPermissionNamesByIds(array $ids): array
    {
        return $this->model->whereIn('id', $ids)->pluck('name')->toArray();
    }
}

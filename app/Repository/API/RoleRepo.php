<?php

namespace App\Repository\API;

use App\Models\Role;
use App\Repository\AbstractRepoService;

class RoleRepo extends AbstractRepoService
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function getRoleNameById(int $id): ?string
    {
        return $this->model->where('id', $id)->value('name');
    }

    public function getValidRoleIdsByIds(array $ids): array
    {
        return $this->model->whereIn('id', $ids)->pluck('id')->toArray();
    }
}

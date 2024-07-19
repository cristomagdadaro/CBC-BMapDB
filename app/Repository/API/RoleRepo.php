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
}

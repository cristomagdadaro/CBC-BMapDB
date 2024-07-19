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
}

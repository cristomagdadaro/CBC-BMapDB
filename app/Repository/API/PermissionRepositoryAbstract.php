<?php

namespace App\Repository\API;

use App\Models\Permission;
use App\Repository\AbstractBaseRepository;

class PermissionRepositoryAbstract extends AbstractBaseRepository
{
    protected array $searchable = [
        'id',
        'label',
        'value',
    ];

    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }
}

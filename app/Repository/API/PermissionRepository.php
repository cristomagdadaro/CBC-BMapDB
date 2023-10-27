<?php

namespace App\Repository\API;

use App\Models\Permission;
use App\Repository\BaseRepository;

class PermissionRepository extends BaseRepository
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

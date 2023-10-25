<?php

namespace App\Repository\API;

use App\Models\Role;
use App\Repository\BaseRepository;

class RoleRepository extends BaseRepository
{
    protected array $searchable = [
        'id',
        'label',
        'value',
    ];

    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}

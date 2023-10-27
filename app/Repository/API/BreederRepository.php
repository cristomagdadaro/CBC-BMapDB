<?php

namespace App\Repository\API;

use App\Models\Breeder;
use App\Repository\BaseRepository;

class BreederRepository extends BaseRepository
{
    protected array $searchable = [
        'name',
        'agency',
        'address',
        'phone',
        'email',
    ];

    public function __construct(Breeder $model)
    {
        parent::__construct($model);
    }
}

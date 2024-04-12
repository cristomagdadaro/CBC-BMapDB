<?php

namespace App\Repository\API;

use App\Models\Breeder;
use App\Repository\AbstractBaseRepository;
use Illuminate\Database\Eloquent\Builder;

class BreederRepositoryAbstract extends AbstractBaseRepository
{
    protected array $searchable = [
        'id',
        'user_id',
        'name',
        'agency',
        'address',
        'phone',
        'email',
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    public function __construct(Breeder $model)
    {
        parent::__construct($model);
    }
}

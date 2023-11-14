<?php

namespace App\Repository\API;

use App\Models\Breeder;
use App\Repository\AbstractBaseRepository;
use Illuminate\Database\Eloquent\Builder;

class BreederRepositoryAbstract extends AbstractBaseRepository
{
    protected array $searchable = [
        'id',
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

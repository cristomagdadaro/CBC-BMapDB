<?php

namespace App\Repository\API;

use App\Models\TWGProduct;
use App\Repository\AbstractBaseRepository;

class TWGProductRepositoryAbstract extends AbstractBaseRepository
{
    protected array $searchable = [
        'id',
        'name',
        'brand',
        'purpose',
        'cost'
    ];

    public function __construct(TWGProduct $model)
    {
        parent::__construct($model);
    }
}

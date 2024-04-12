<?php

namespace App\Repository\API;

use App\Models\Commodity;
use App\Repository\AbstractBaseRepository;

class CommodityRepositoryAbstract extends AbstractBaseRepository
{
    /**
     * @var array
     */
    protected array $searchable = [
        'id',
        'name',
        'breeder_id',
        'scientific_name',
        'variety',
        'accession',
        'germplasm',
        'population',
        'maturity_period',
        'yield',
        'description',
        'image',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function __construct(Commodity $model)
    {
        parent::__construct($model);
    }
}

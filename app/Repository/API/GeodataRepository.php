<?php

namespace App\Repository\API;

use App\Models\Geodata;
use App\Repository\BaseRepository;

class GeodataRepository extends BaseRepository
{
    protected array $searchable = [
        'breeder_id',
        'latitude',
        'longitude',
        'city',
        'province',
        'country',
    ];

    public function __construct(Geodata $model)
    {
        parent::__construct($model);
    }
}

<?php

namespace App\Repository\API;

use App\Models\Location\City;
use App\Repository\AbstractRepoService;

class CityRepo extends AbstractRepoService
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }
}

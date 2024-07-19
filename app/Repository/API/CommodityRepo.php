<?php

namespace App\Repository\API;

use App\Models\Commodity;
use App\Repository\AbstractRepoService;

class CommodityRepo extends AbstractRepoService
{
    public function __construct(Commodity $model)
    {
        parent::__construct($model);
    }
}

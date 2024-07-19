<?php

namespace App\Repository\API;

use App\Models\Breeder;
use App\Repository\AbstractRepoService;

class BreederRepo extends AbstractRepoService
{
    public function __construct(Breeder $model)
    {
        parent::__construct($model);
    }
}

<?php

namespace App\Repository\API;

use App\Models\Geodata;
use App\Repository\AbstractRepoService;

class GeodataRepo extends AbstractRepoService
{
    public function __construct(Geodata $model)
    {
        parent::__construct($model);
    }
}

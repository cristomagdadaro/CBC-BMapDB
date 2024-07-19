<?php

namespace App\Repository\API;

use App\Models\TWGService;
use App\Repository\AbstractRepoService;

class TWGServiceRepo extends AbstractRepoService
{
    public function __construct(TWGService $model)
    {
        parent::__construct($model);
    }
}

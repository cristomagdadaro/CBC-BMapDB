<?php

namespace App\Repository\API;

use App\Models\TWGExpert;
use App\Repository\AbstractRepoService;

class TWGExpertRepo extends AbstractRepoService
{
    public function __construct(TWGExpert $model)
    {
        parent::__construct($model);
    }
}

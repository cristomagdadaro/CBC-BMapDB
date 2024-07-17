<?php

namespace App\Repository\API;

use App\Models\TWGProduct;
use App\Repository\AbstractRepoService;

class TWGProductRepo extends AbstractRepoService
{
    public function __construct(TWGProduct $model)
    {
        parent::__construct($model);
    }
}

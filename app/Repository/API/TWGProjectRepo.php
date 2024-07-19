<?php

namespace App\Repository\API;

use App\Models\TWGProject;
use App\Repository\AbstractRepoService;

class TWGProjectRepo extends AbstractRepoService
{
    public function __construct(TWGProject $model)
    {
        parent::__construct($model);
    }
}

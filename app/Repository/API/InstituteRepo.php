<?php

namespace App\Repository\API;

use App\Models\Institute;
use App\Repository\AbstractRepoService;

class InstituteRepo extends AbstractRepoService
{
    public function __construct(Institute $model)
    {
        parent::__construct($model);
    }
}

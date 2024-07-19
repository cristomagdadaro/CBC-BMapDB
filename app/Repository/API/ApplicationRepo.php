<?php

namespace App\Repository\API;

use App\Models\Application;
use App\Repository\AbstractRepoService;

class ApplicationRepo extends AbstractRepoService
{
    public function __construct(Application $model)
    {
        parent::__construct($model);
    }
}

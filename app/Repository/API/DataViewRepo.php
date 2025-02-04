<?php

namespace App\Repository\API;

use App\Models\DataView;
use App\Repository\AbstractRepoService;

class DataViewRepo extends AbstractRepoService
{
    public function __construct(DataView $model)
    {
        parent::__construct($model);
    }
}

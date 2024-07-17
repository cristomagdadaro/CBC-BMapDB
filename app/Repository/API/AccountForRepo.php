<?php

namespace App\Repository\API;

use App\Models\AccountFor;
use App\Repository\AbstractRepoService;

class AccountForRepo extends AbstractRepoService
{
    public function __construct(AccountFor $model)
    {
        parent::__construct($model);
    }
}

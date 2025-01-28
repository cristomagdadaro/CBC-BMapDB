<?php

namespace App\Repository\API;

use App\Modules\Administrator\Models\Accounts;
use App\Repository\AbstractRepoService;

class AccountsRepo extends AbstractRepoService
{
    public function __construct(Accounts $model)
    {
        parent::__construct($model);
    }
}

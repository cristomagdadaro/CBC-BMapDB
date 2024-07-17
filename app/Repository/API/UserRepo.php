<?php

namespace App\Repository\API;

use App\Models\User;
use App\Repository\AbstractRepoService;

class UserRepo extends AbstractRepoService
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}

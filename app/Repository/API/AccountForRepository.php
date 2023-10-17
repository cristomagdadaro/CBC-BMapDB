<?php

namespace App\Repository\API;

use App\Models\AccountFor;
use App\Repository\BaseRepository;
use Illuminate\Support\Collection;

class AccountForRepository extends BaseRepository
{
    public function __construct(AccountFor $model)
    {
        parent::__construct($model);
    }
}

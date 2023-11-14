<?php

namespace App\Repository\API;

use App\Models\AccountFor;
use App\Repository\AbstractBaseRepository;
use Illuminate\Support\Collection;

class AccountForRepositoryAbstract extends AbstractBaseRepository
{
    protected array $searchable = [
        'user_id',
        'app_id',
        'account_id',
    ];

    public function __construct(AccountFor $model)
    {
        parent::__construct($model);
    }
}

<?php

namespace App\Repository\API;

use App\Models\Accounts;
use App\Repository\AbstractBaseRepository;
use Illuminate\Support\Collection;

class AccountsRepositoryAbstract extends AbstractBaseRepository
{
    protected array $searchable = [
        'user_id',
        'app_id',
        'account_id',
    ];

    public function __construct(Accounts $model)
    {
        parent::__construct($model);
    }
}

<?php

namespace App\Repository\API;

use App\Models\AccountFor;
use App\Repository\BaseRepository;
use Illuminate\Support\Collection;

class AccountForRepository extends BaseRepository
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

<?php

namespace App\Repository\API;

use App\Models\User;
use App\Repository\BaseRepository;

class UserRepository extends BaseRepository
{
    protected array $searchable = [
        'id',
        'fname',
        'mname',
        'lname',
        'suffix',
        'account_for',
        'email',
        'mobile_no',
    ];

    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}

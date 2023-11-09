<?php

namespace App\Repository\API;

use App\Models\Breeder;
use App\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class BreederRepository extends BaseRepository
{
    protected array $searchable = [
        'id',
        'name',
        'agency',
        'address',
        'phone',
        'email',
    ];

    public function __construct(Breeder $model)
    {
        parent::__construct($model);
    }
}

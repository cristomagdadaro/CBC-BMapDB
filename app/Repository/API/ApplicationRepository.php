<?php

namespace App\Repository\API;

use App\Models\Application;
use App\Repository\BaseRepository;

class ApplicationRepository extends BaseRepository
{
    protected array $searchable = [
        'name',
        'description',
        'url',
        'icon',
    ];

    public function __construct(Application $model)
    {
        parent::__construct($model);
    }
}

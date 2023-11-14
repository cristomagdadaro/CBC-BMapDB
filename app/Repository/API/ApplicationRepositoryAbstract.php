<?php

namespace App\Repository\API;

use App\Models\Application;
use App\Repository\AbstractBaseRepository;

class ApplicationRepositoryAbstract extends AbstractBaseRepository
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

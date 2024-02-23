<?php

namespace App\Repository\API;

use App\Models\TWGExpert;
use App\Repository\AbstractBaseRepository;

class TWGExpertRepositoryAbstract extends AbstractBaseRepository
{
    protected array $searchable = [
        'id',
        'user_id',
        'name',
        'position',
        'educ_level',
        'expertise',
        'research_interest',
        'mobile',
        'email',
    ];

    public function __construct(TWGExpert $model)
    {
        parent::__construct($model);
    }
}

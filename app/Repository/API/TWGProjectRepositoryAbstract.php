<?php

namespace App\Repository\API;

use App\Models\TWGProject;
use App\Repository\AbstractBaseRepository;

class TWGProjectRepositoryAbstract extends AbstractBaseRepository
{
    protected array $searchable = [
        'id',
        'twg_expert_id',
        'title',
        'objective',
        'expected_output',
        'project_leader',
        'funding_agency',
        'duration',
        'status'
    ];

    public function __construct(TWGProject $model)
    {
        parent::__construct($model);
    }
}

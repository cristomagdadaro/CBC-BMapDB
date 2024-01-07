<?php

namespace App\Repository\API;

use App\Models\TWGService;
use App\Repository\AbstractBaseRepository;

class TWGServiceRepositoryAbstract extends AbstractBaseRepository
{
    protected array $searchable = [
        'id',
        'type',
        'purpose',
        'direct_beneficiaries',
        'indirect_beneficiaries',
        'officer_in_charge',
        'cost'
    ];

    public function __construct(TWGService $model)
    {
        parent::__construct($model);
    }
}

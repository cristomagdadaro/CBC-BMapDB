<?php

namespace App\Repository\API\Inventory;

use App\Models\Inventory\Supplier;
use App\Repository\BaseRepository;

class SupplierRepository extends BaseRepository
{
    protected array $searchable = [
        'name',
        'email',
        'phone',
        'address',
        'description',
    ];

    public function __construct(Supplier $model)
    {
        parent::__construct($model);
    }
}

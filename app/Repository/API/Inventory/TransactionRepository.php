<?php

namespace App\Repository\API\Inventory;

use App\Models\Inventory\Transaction;
use App\Repository\BaseRepository;

class TransactionRepository extends BaseRepository
{
    protected array $searchable = [
        'id',
        'barcode',
        'item_id',
        'transac_type',
        'quantity',
        'unit',
        'unit_price',
        'total_cost',
        'expiration',
        'supplier_id',
        'remarks',
    ];

    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }
}

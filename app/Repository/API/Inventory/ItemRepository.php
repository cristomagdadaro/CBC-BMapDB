<?php

namespace App\Repository\API\Inventory;

use App\Models\Inventory\Item;
use App\Repository\BaseRepository;

class ItemRepository extends BaseRepository
{
    protected array $searchable = [
        'id',
        'name',
        'brand',
        'description',
        'category_id',
        'image',
    ];

    public function __construct(Item $model)
    {
        parent::__construct($model);
    }
}

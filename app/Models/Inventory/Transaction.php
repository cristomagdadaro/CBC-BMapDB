<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'item_id',
        'transac_type',
        'quantity',
        'unit',
        'cost',
        'supplier_id',
        'remarks',
        'status',
    ];
}

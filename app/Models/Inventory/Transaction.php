<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transactions';

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'id',
        'item_id',
        'barcode',
        'transac_type',
        'quantity',
        'unit_price',
        'unit',
        'total_cost',
        'personnel_id',
        'supplier_id',
        'expiration',
        'remarks',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'personnel_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

}

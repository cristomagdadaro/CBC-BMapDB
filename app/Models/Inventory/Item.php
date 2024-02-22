<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items';

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'id',
        'name',
        'brand',
        'description',
        'category_id',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'item_id', 'id');
    }
}

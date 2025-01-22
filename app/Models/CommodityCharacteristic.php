<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommodityCharacteristic extends BaseModel
{
    use HasFactory;

    protected $table = 'commodity_characteristics';

    protected $fillable = [
        'commodity_id',
        'weight',
        'length',
        'width',
        'shape',

        // Fruit Characteristics
        'skin_color',
        'skin_texture',
        'flesh_color',
        'flesh_texture',
        'flesh_flavor',
        'aroma',

        // Root Characteristics
        'root_flesh_color',
        'root_cortex_color',
        'root_skin_color',
        'root_shape',

        // Tuber Characteristics
        'tuber_flesh_color',
        'tuber_cortex_color',
        'tuber_skin_color',
        'tuber_shape',
    ];

    protected array $searchable = [
        'commodity_id',
        'weight',
        'length',
        'width',
        'shape',
        'skin_color',
        'skin_texture',
        'flesh_color',
        'flesh_texture',
        'flesh_flavor',
        'aroma',
        'root_flesh_color',
        'root_cortex_color',
        'root_skin_color',
        'root_shape',
        'tuber_flesh_color',
        'tuber_cortex_color',
        'tuber_skin_color',
        'tuber_shape',
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }
}

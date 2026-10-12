<?php

namespace Modules\PbMap\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommodityRequest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'scientific_name', 'status'];
}

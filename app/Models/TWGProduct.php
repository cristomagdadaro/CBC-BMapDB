<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TWGProduct extends Model
{
    use HasFactory;

    protected $table = 'twg_product';

    protected $fillable = [
        'id',
        'name',
        'brand',
        'purpose',
        'cost'
    ];
}

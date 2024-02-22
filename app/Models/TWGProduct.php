<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'twg_product';

    protected $fillable = [
        'twg_expert_id',
        'name',
        'brand',
        'purpose',
        'cost'
    ];
}

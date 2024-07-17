<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGProduct extends BaseModel
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

    protected array $searchable = [
        'id',
        'name',
        'brand',
        'purpose',
        'cost'
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('g:i a M j, Y');
    }
}

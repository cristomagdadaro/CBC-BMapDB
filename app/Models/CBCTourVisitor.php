<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CBCTourVisitor extends Model
{
    use HasFactory;

    protected $table = 'cbctour_visitors';

    protected $fillable = [
        'ip_address',
        'method',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}


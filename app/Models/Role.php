<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends BaseModel
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'label',
        'value',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected array $searchable = [
        'id',
        'label',
        'value',
    ];
}

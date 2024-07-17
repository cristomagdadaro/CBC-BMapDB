<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends BaseModel
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'role_id',
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

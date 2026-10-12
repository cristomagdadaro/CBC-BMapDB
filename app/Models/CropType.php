<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class CropType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'scientific_name',
        'icon_url',
        'attributes_schema',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'attributes_schema' => AsArrayObject::class,
    ];
}

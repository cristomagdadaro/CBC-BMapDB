<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Variety extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'crop_type_id',
        'institute_id',
        'name',
        'status',
        'attributes',
        'approved_at',
        'approved_by',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'attributes' => AsArrayObject::class,
        'approved_at' => 'datetime',
    ];

    public function cropType(): BelongsTo
    {
        return $this->belongsTo(CropType::class);
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function locations(): MorphToMany
    {
        return $this->morphToMany(Location::class, 'locationable', 'locationables');
    }
}

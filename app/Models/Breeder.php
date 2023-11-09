<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Breeder extends Model
{
    use HasFactory;

    protected $table = 'breeders';

    protected $fillable = [
        'name',
        'agency',
        'address',
        'phone',
        'email',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function geodata(): HasMany
    {
        return $this->hasMany(Geodata::class, 'breeder_id', 'id');
    }
}

<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Breeder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'breeders';

    protected $fillable = [
        'user_id',
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

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('g:i a M j, Y');
    }

    /*protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
    ];*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function geodata(): HasMany
    {
        return $this->hasMany(Geodata::class, 'breeder_id', 'id');
    }

    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class, 'breeder_id', 'id');
    }
}

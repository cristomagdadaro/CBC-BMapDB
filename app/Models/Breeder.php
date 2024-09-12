<?php

namespace App\Models;

use App\Models\Location\City;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Model;

class Breeder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'breeders';

    protected $fillable = [
        'user_id',
        'name',
        'agency',
        'city',
        'province',
        'region',
        'country',
        'phone',
        'email',
        'password',
        'remember_token'
    ];

    protected $guarded = ['id'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected array $searchable = [
        'id',
        'user_id',
        'name',
        'agency',
        'city',
        'province',
        'region',
        'country',
        'phone',
        'email',
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected array $notifMessage = [
        'created' => 'Breeder created successfully.',
        'updated' => 'Breeder updated successfully.',
        'deleted' => 'Breeder deleted successfully.',
        'restored' => 'Breeder restored successfully.',
        'forceDeleted' => 'Breeder permanently deleted.',
        'emptyTrash' => 'Breeder deleted successfully.',
        'notFound' => 'Breeder not found.',
        'unknown' => 'Unknown error, action failed.',
    ];

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

    public function getSearchable(): array
    {
        return $this->searchable;
    }

    public function cityDesc(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city', 'cityDesc');
    }
}

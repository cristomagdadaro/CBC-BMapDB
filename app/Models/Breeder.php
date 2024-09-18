<?php

namespace App\Models;

use App\Models\Location\City;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Model;

class Breeder extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'breeders';

    protected $fillable = [
        'user_id',
        'name',
        'affiliation',
        'geolocation',
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
        'breeders.id',
        'user_id',
        'name',
        'affiliation',
        'geolocation',
        'phone',
        'email',
        'breeders.created_at',
        'breeders.updated_at',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class, 'breeder_id', 'id');
    }

    public function getSearchable(): array
    {
        return $this->searchable;
    }

    public function location()
    {
        return $this->belongsTo(City::class, 'geolocation')
            ->select((new City())->getSearchable());
    }

    public function affiliated(): BelongsTo
    {
        return $this->belongsTo(Institute::class, 'affiliation', 'id');
    }

    // Scope a query to only include commodities that belong to the same institute
    public function scopeOfModel($query, $id)
    {
        return $query->where('user_id', $id);
    }
}

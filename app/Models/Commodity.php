<?php

namespace App\Models;

use App\Models\Location\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commodity extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'commodities';

    protected $fillable = [
        'name',
        'breeder_id',
        'scientific_name',
        'variety',
        'accession',
        'germplasm',
        'population',
        'maturity_period',
        'yield',
        'description',
        'image',
        'geolocation',
        'status',
    ];

    protected array $searchable = [
        'commodities.id',
        'name',
        'breeder_id',
        'scientific_name',
        'variety',
        'accession',
        'germplasm',
        'population',
        'maturity_period',
        'yield',
        'description',
        'image',
        'geolocation',
        'status',
        'commodities.created_at',
        'commodities.updated_at',
    ];

    protected $casts = [
        'yield' => 'integer',
        'population' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected array $notifMessage = [
        'created' => 'Commodity created successfully.',
        'updated' => 'Commodity updated successfully.',
        'deleted' => 'Commodity deleted successfully.',
        'restored' => 'Commodity restored successfully.',
        'forceDeleted' => 'Commodity permanently deleted.',
        'emptyTrash' => 'Commodity deleted successfully.',
        'notFound' => 'Commodity not found.',
        'unknown' => 'Unknown error, action failed.',
    ];

    public function breeder(): BelongsTo
    {
        return $this->belongsTo(Breeder::class, 'breeder_id', 'id')
            ->select((new Breeder())->getSearchable());
    }

    public function location()
    {
        return $this->belongsTo(City::class, 'geolocation')
            ->select((new City())->getSearchable());
    }

    // Scope a query to only include commodities that belong to a specific breeder
    public function scopeOfModel($query, $breeder_id)
    {
        return $query->where('breeder_id', $breeder_id);
    }
}

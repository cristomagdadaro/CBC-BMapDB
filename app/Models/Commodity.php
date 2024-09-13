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
        'latitude',
        'longitude',
        'city',
        'province',
        'region',
        'country',
    ];

    protected array $searchable = [
        'id',
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
        'city',
        'province',
        'region',
        'country',
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
        return $this->belongsTo(Breeder::class, 'breeder_id', 'id');
    }

    public function cityDesc(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city', 'cityDesc');
    }
}

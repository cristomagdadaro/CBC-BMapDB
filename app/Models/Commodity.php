<?php

namespace App\Models;

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
        'commodities.deleted_at',
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

    public function user(): BelongsTo
    {
        return $this->breeder()->with('user');
    }

    public function breeder(): BelongsTo
    {
        return $this->belongsTo(Breeder::class, 'breeder_id', 'id')
            ->select((new Breeder())->getSearchable())->withTrashed();
    }

    // Scope a query to only include commodities that belong to a specific breeder
    public function scopeOfModel($query, $breeder_id)
    {
        return $query->where('breeder_id', $breeder_id)->with('user')
            ->whereHas('user', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }
}

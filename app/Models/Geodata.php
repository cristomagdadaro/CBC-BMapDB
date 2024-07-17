<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geodata extends BaseModel
{
    use HasFactory;

    protected $table = 'geodata';

    protected $fillable = [
        'breeder_id',
        'latitude',
        'longitude',
        'address',
        'city',
        'province',
        'country',
        'postal_code',
        'formatted_address',
        'place_id',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected array $searchable = [
        'breeder_id',
        'latitude',
        'longitude',
        'city',
        'province',
        'country',
    ];

    protected array $notifMessage = [
        'created' => 'Geodata created successfully.',
        'updated' => 'Geodata updated successfully.',
        'deleted' => 'Geodata deleted successfully.',
        'restored' => 'Geodata restored successfully.',
        'forceDeleted' => 'Geodata permanently deleted.',
        'emptyTrash' => 'Geodata deleted successfully.',
        'notFound' => 'Geodata not found.',
        'unknown' => 'Unknown error, action failed.',
    ];


    public function breeder()
    {
        return $this->belongsTo(Breeder::class);
    }
}

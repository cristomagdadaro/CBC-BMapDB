<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geodata extends Model
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

    public function breeder()
    {
        return $this->belongsTo(Breeder::class);
    }
}

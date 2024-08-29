<?php

namespace App\Models\Location;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Region extends BaseModel
{
    use HasFactory;

    protected $table = 'loc_regions';

    protected $fillable = [
        'psgcCode',
        'regDesc',
        'regCode',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function provinces(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Province::class, 'regCode', 'regCode');
    }

    public function cities()
    {
        return $this->hasManyThrough(City::class, Province::class, 'regCode', 'provCode', 'regCode', 'provCode');
    }
}

<?php

namespace App\Models\Location;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends BaseModel
{
    use HasFactory;

    protected $table = 'loc_countries';

    protected $fillable = [
        'country',
        'country_id',
    ];

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id', 'country_id');
    }

    public function provinces()
    {
        return $this->hasMany(Province::class, 'country_id', 'country_id');
    }

    public function regions()
    {
        return $this->hasMany(Region::class, 'country_id', 'country_id');
    }
}

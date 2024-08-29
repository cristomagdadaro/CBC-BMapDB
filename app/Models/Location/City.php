<?php

namespace App\Models\Location;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends BaseModel
{
    use HasFactory;

    protected $table = 'loc_cities';

    protected $fillable = [
        'latitude',
        'longitude',
        'psgcCode',
        'citymunDesc',
        'regDesc',
        'provDesc',
        'countryDesc',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'provCode', 'provCode');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'regDesc', 'regDesc');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'countryDesc', 'countryDesc');
    }
}

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
        'cityDesc',
        'provDesc',
        'regDesc',
    ];

    protected array $searchable = [
        'id',
        'latitude',
        'longitude',
        'cityDesc',
        'provDesc',
        'regDesc',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'provDesc', 'provDesc');
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

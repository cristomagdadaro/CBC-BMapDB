<?php

namespace App\Models\Location;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Province extends BaseModel
{
    use HasFactory;

    protected $table = 'loc_provinces';

    protected $fillable = [
        'provDesc',
        'regDesc',
    ];

    public function cities()
    {
        return $this->hasMany(City::class, 'provCode', 'provCode');
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

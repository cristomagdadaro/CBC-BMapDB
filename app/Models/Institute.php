<?php

namespace App\Models;

use App\Models\Location\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institute extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'inst_type',
        'geolocation',
        'website',
        'email',
        'phone',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected array $searchable = [
        'id',
        'name',
        'inst_type',
        'geolocation',
        'website',
        'email',
        'phone',
    ];


    public function city()
    {
        return $this->belongsTo(City::class, 'geolocation')
            ->select((new City())->getSearchable());
    }
}

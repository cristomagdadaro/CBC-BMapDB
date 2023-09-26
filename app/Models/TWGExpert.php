<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TWGExpert extends Model
{
    use HasFactory;

    protected $table = 'twg_expert';

    protected $fillable = [
        'id',
        'fname',
        'mname', // 'mname' is the middle name
        'lname',
        'suffix',
        'position',
        'educ_level',
        'expertise',
        'email',
        'mobile_no',
    ];

    public function twg_projects()
    {
        return $this->hasMany(TWGProject::class, 'twg_expert_id', 'id');
    }

    public function twg_services()
    {
        return $this->hasMany(TWGService::class, 'twg_expert_id', 'id');
    }

    public function twg_products()
    {
        return $this->hasMany(TWGProduct::class, 'twg_expert_id', 'id');
    }
}

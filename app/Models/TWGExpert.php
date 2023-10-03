<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TWGExpert extends Model
{
    use HasFactory;

    protected $table = 'twg_expert';

    protected $fillable = [
        'user_id',
        'position',
        'educ_level',
        'expertise',
    ];
}

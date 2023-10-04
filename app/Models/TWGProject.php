<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TWGProject extends Model
{
    use HasFactory;

    protected $table = 'twg_project';

    protected $fillable = [
        'id',
        'twg_expert_id',
        'title',
        'objective',
        'expected_output',
        'project_leader',
        'funding_agency',
        'duration',
        'status'
    ];


}

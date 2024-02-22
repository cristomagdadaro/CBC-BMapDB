<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'twg_project';

    protected $fillable = [
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

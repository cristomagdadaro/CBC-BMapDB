<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGExpert extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'twg_expert';

    protected $fillable = [
        'user_id',
        'name',
        'position',
        'educ_level',
        'expertise',
        'research_interest',
        'mobile',
        'email',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('g:i a M j, Y');
    }

    public function twg_projects()
    {
        return $this->hasMany(TWGProject::class, 'twg_expert_id','user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGExpert extends BaseModel
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

    protected array $searchable = [
        'id',
        'user_id',
        'name',
        'position',
        'educ_level',
        'expertise',
        'research_interest',
        'mobile',
        'email',
        'twg_expert.created_at',
        'twg_expert.updated_at',
        'twg_expert.deleted_at',
    ];

    protected array $notifMessage = [
        'created' => 'TWG Expert created successfully.',
        'updated' => 'TWG Expert updated successfully.',
        'deleted' => 'TWG Expert deleted successfully.',
        'restored' => 'TWG Expert restored successfully.',
        'forceDeleted' => 'TWG Expert permanently deleted.',
        'emptyTrash' => 'TWG Expert deleted successfully.',
        'notFound' => 'TWG Expert not found.',
        'unknown' => 'Unknown error, action failed.',
    ];

    public function projects()
    {
        return $this->hasMany(TWGProject::class, 'twg_expert_id','id');
    }

    public function products()
    {
        return $this->hasMany(TWGProduct::class, 'twg_expert_id','id');
    }

    public function services()
    {
        return $this->hasMany(TWGService::class,'twg_expert_id','id');
    }
}

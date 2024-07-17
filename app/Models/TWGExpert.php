<?php

namespace App\Models;

use DateTimeInterface;
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
        'updated_at',
        'created_at',
        'deleted_at',
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

    public function twg_projects()
    {
        return $this->hasMany(TWGProject::class, 'twg_expert_id','user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGProject extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'twg_project';

    protected $fillable = [
        'user_id',
        'twg_expert_id',
        'title',
        'objective',
        'expected_output',
        'project_leader',
        'funding_agency',
        'duration',
        'status'
    ];

    protected array $searchable = [
        'id',
        'user_id',
        'twg_expert_id',
        'title',
        'objective',
        'expected_output',
        'project_leader',
        'funding_agency',
        'duration',
        'status',
        'twg_project.created_at',
        'twg_project.updated_at',
        'twg_project.deleted_at',
    ];

    protected array $notifMessage = [
        'created' => 'TWG Project created successfully.',
        'updated' => 'TWG Project updated successfully.',
        'deleted' => 'TWG Project deleted successfully.',
        'restored' => 'TWG Project restored successfully.',
        'forceDeleted' => 'TWG Project permanently deleted.',
        'emptyTrash' => 'TWG Project deleted successfully.',
        'notFound' => 'TWG Project not found.',
        'unknown' => 'Unknown error, action failed.',
    ];

    public function expert()
    {
        return $this->belongsTo(TWGExpert::class, 'twg_expert_id');
    }
}

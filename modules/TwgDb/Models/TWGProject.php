<?php

namespace Modules\TwgDb\Models;

use App\Models\BaseModel;
use App\Models\Institute;
use App\Models\User;
use App\Traits\OwnedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGProject extends BaseModel
{
    use HasFactory, SoftDeletes, OwnedByTrait;

    protected $table = 'twg_project';

    protected $fillable = [
        'user_id',
        'institution',
        'title',
        'objective',
        'expected_output',
        'project_leader',
        'funding_agency',
        'duration',
        'status'
    ];

    protected array $searchable = [
        'twg_project.id',
        'twg_project.user_id',
        'twg_project.institution',
        'twg_project.title',
        'twg_project.objective',
        'twg_project.expected_output',
        'twg_project.project_leader',
        'twg_project.funding_agency',
        'twg_project.duration',
        'twg_project.status',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function projectLeader()
    {
        return $this->belongsTo(TWGExpert::class, 'project_leader', 'id');
    }

    public function affiliated(): BelongsTo
    {
        return $this->belongsTo(Institute::class, 'institution', 'id');
    }
}

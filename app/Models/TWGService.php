<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGService extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'twg_service';

    protected $fillable = [
        'user_id',
        'institution',
        'type',
        'purpose',
        'direct_beneficiaries',
        'indirect_beneficiaries',
        'officer_in_charge',
        'cost'
    ];

    protected array $searchable = [
        'id',
        'user_id',
        'institution',
        'type',
        'purpose',
        'direct_beneficiaries',
        'indirect_beneficiaries',
        'officer_in_charge',
        'cost',
        'twg_service.created_at',
        'twg_service.updated_at',
        'twg_service.deleted_at',
    ];

    protected array $notifMessage = [
        'created' => 'TWG Service created successfully.',
        'updated' => 'TWG Service updated successfully.',
        'deleted' => 'TWG Service deleted successfully.',
        'restored' => 'TWG Service restored successfully.',
        'forceDeleted' => 'TWG Service permanently deleted.',
        'emptyTrash' => 'TWG Service deleted successfully.',
        'notFound' => 'TWG Service not found.',
        'unknown' => 'Unknown error, action failed.',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function affiliated(): BelongsTo
    {
        return $this->belongsTo(Institute::class, 'institution', 'id');
    }

    public function officerInCharge()
    {
        return $this->belongsTo(TWGExpert::class, 'officer_in_charge', 'id');
    }
}

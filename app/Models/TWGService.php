<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGService extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'twg_service';

    protected $fillable = [
        'twg_expert_id',
        'type',
        'purpose',
        'direct_beneficiaries',
        'indirect_beneficiaries',
        'officer_in_charge',
        'cost'
    ];

    protected array $searchable = [
        'id',
        'twg_expert_id',
        'type',
        'purpose',
        'direct_beneficiaries',
        'indirect_beneficiaries',
        'officer_in_charge',
        'cost'
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGProduct extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'twg_product';

    protected $fillable = [
        'twg_expert_id',
        'name',
        'brand',
        'purpose',
        'cost'
    ];

    protected array $searchable = [
        'id',
        'twg_expert_id',
        'name',
        'brand',
        'purpose',
        'cost'
    ];

    protected array $notifMessage = [
        'created' => 'TWG Product created successfully.',
        'updated' => 'TWG Product updated successfully.',
        'deleted' => 'TWG Product deleted successfully.',
        'restored' => 'TWG Product restored successfully.',
        'forceDeleted' => 'TWG Product permanently deleted.',
        'emptyTrash' => 'TWG Product deleted successfully.',
        'notFound' => 'TWG Product not found.',
        'unknown' => 'Unknown error, action failed.',
    ];

    public function expert()
    {
        return $this->belongsTo(TWGExpert::class, 'twg_expert_id');
    }
}

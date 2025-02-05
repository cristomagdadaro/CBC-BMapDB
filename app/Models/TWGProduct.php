<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGProduct extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'twg_product';

    protected $fillable = [
        'user_id',
        'institution',
        'name',
        'brand',
        'purpose',
        'cost'
    ];

    protected array $searchable = [
        'id',
        'user_id',
        'institution',
        'name',
        'brand',
        'purpose',
        'cost',
        'twg_product.created_at',
        'twg_product.updated_at',
        'twg_product.deleted_at',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function affiliated(): BelongsTo
    {
        return $this->belongsTo(Institute::class, 'institution', 'id');
    }
}

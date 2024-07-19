<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends BaseModel
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'role_id',
        'label',
        'value',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected array $searchable = [
        'id',
        'label',
        'value',
    ];

    protected array $notifMessage = [
        'created' => 'Permission created successfully.',
        'updated' => 'Permission updated successfully.',
        'deleted' => 'Permission deleted successfully.',
        'restored' => 'Permission restored successfully.',
        'forceDeleted' => 'Permission permanently deleted.',
        'emptyTrash' => 'Permission deleted successfully.',
        'notFound' => 'Permission not found.',
        'unknown' => 'Unknown error, action failed.',
    ];
}

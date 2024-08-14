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
        'created' => 'Role created successfully.',
        'updated' => 'Role updated successfully.',
        'deleted' => 'Role deleted successfully.',
        'restored' => 'Role restored successfully.',
        'forceDeleted' => 'Role permanently deleted.',
        'emptyTrash' => 'Role deleted successfully.',
        'notFound' => 'Role not found.',
        'unknown' => 'Unknown error, action failed.',
    ];
}

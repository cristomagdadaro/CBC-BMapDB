<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends BaseModel
{
    use HasFactory;

    protected $table = 'roles';

    protected $ignoreUserBasedFiltratration = true;

    protected $fillable = [
        'name',
        'guard_name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected array $searchable = [
        'id',
        'name',
        'guard_name',
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

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }
}

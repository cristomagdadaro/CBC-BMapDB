<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'applications';

    protected $ignoreUserBasedFiltratration = true;


    protected $fillable = [
        'name',
        'description',
        'url',
        'icon',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected array $searchable = [
        'id',
        'name',
        'description',
        'url',
        'icon',
        'status',
    ];

    protected array $notifMessage = [
        'created' => 'Application created successfully.',
        'updated' => 'Application updated successfully.',
        'deleted' => 'Application deleted successfully.',
        'restored' => 'Application restored successfully.',
        'forceDeleted' => 'Application permanently deleted.',
        'emptyTrash' => 'Application deleted successfully.',
        'notFound' => 'Application not found.',
        'unknown' => 'Unknown error, action failed.',
    ];

    public function account()
    {

        return $this->belongsTo(Accounts::class, 'app_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }
}

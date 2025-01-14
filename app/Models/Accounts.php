<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accounts extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'accounts';

    protected $fillable = [
        'user_id',
        'app_id',
        'approved_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected array $searchable = [
        'id',
        'user_id',
        'app_id',
        'approved_at',
    ];

    protected array $notifMessage = [
        'created' => 'Account created successfully.',
        'updated' => 'Account updated successfully.',
        'deleted' => 'Account deleted successfully.',
        'restored' => 'Account restored successfully.',
        'forceDeleted' => 'Account permanently deleted.',
        'emptyTrash' => 'Account deleted successfully.',
        'notFound' => 'Account not found.',
        'unknown' => 'Unknown error, action failed.',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->with(['roles','permissions']);
    }

    public function application()
    {
        return $this->hasOne(Application::class, 'id', 'app_id');
    }

}

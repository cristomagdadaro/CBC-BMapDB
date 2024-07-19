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
        'account_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected array $searchable = [
        'user_id',
        'app_id',
        'account_id',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'account_id', 'id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'app_id', 'id');
    }
}

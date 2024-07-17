<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountFor extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'account_for';

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
        'created' => 'Account for created.',
        'updated' => 'Account for updated.',
        'deleted' => 'Account for deleted.',
        'restored' => 'Account for restored.',
        'forceDeleted' => 'Account for permanently deleted.',
        'emptyTrash' => 'Account for deleted.',
        'notFound' => 'Account for not found.',
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountFor extends Model
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

    protected $searchable = [
        'user_id',
        'app_id',
        'account_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'account_id', 'id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'app_id', 'id');
    }

    public function getSearchable()
    {
        return $this->searchable;
    }
}

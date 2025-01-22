<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    use HasFactory;

    protected $table = 'api_request_logs';

    protected $fillable = [
        'user_id',
        'user_role',
        'ip_address',
        'method',
        'url',
        'model',
        'data',
        'modified_id',
    ];
}

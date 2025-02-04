<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataView extends BaseModel
{
    use HasFactory;

    protected $fillable = [
      'uuid',
      'user_account_id',
      'model',
      'columns',
      'visibility_guard'
    ];

    protected array $searchable = [
        'uuid',
        'user_account_id',
        'model',
        'columns',
        'visibility_guard'
    ];
}

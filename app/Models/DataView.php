<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataView extends BaseModel
{
    use HasFactory;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

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

    protected $casts = [
        'columns' => 'array',  // This will automatically cast it to an array on retrieval and store it as JSON in the database
    ];
}

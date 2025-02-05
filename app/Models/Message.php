<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Message extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'id',
        'to_user',
        'from_user',
        'message',
        'replied_to'
    ];

    protected array $searchable = [
        'id',
        'to_user',
        'from_user',
        'message',
        'replied_to'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * A message belong to a user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'to_user', 'id');
    }

    /**
     * A message belong to a user
     *
     * @return BelongsTo
     */
    public function toUser()
    {
        return $this->user();
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user', 'id');
    }
}

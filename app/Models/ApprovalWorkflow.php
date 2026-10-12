<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class ApprovalWorkflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'morphable_type',
        'morphable_id',
        'submitted_by',
        'reviewer_id',
        'status',
        'payload',
        'reviewer_remarks',
    ];

    protected $casts = [
        'payload' => AsArrayObject::class,
    ];

    public function morphable(): MorphTo
    {
        return $this->morphTo();
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}

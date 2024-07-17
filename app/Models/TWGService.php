<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TWGService extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'twg_service';

    protected $fillable = [
        'twg_expert_id',
        'type',
        'purpose',
        'direct_beneficiaries',
        'indirect_beneficiaries',
        'officer_in_charge',
        'cost'
    ];

    protected array $searchable = [
        'id',
        'type',
        'purpose',
        'direct_beneficiaries',
        'indirect_beneficiaries',
        'officer_in_charge',
        'cost'
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('g:i a M j, Y');
    }
}

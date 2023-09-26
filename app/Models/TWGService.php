<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TWGService extends Model
{
    use HasFactory;

    protected $table = 'twg_service';

    protected $fillable = [
        'id',
        'type',
        'purpose',
        'direct_beneficiaries',
        'indirect_beneficiaries',
        'officer_in_charge',
        'cost'
    ];
}

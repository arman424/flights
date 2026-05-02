<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aircraft extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'airline_code',
        'aircraft_type',
        'flight_number',
        'registration',
    ];
}


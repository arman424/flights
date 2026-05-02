<?php

namespace App\Models;

use App\Enums\CabinClassCode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Segment extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'leg_id',
        'segment_index',
        'origin',
        'destination',
        'departure',
        'arrival',
        'cabin_class',
        'airline_code',
        'flight_number',
    ];

    protected $casts = [
        'departure'   => 'datetime',
        'arrival'     => 'datetime',
        'cabin_class' => CabinClassCode::class,
    ];

    public function leg(): BelongsTo
    {
        return $this->belongsTo(Leg::class);
    }
}

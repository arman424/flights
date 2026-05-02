<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leg extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = ['flight_id', 'leg_index'];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    public function segments(): HasMany
    {
        return $this->hasMany(Segment::class)->orderBy('segment_index');
    }
}

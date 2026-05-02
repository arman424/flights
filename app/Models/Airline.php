<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Airline extends Model
{
    use HasUuids;

    protected $fillable = ['code', 'name'];

    public function segments(): HasMany
    {
        return $this->hasMany(Segment::class, 'airline_code', 'code');
    }
}


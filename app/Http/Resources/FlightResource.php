<?php

namespace App\Http\Resources;

use App\Domain\Flight\Snapshots\FlightSnapshot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
{
    public function __construct(private readonly FlightSnapshot $flightSnapshot)
    {
        parent::__construct($flightSnapshot);
    }

    public function toArray(Request $request): array
    {
        return [
            'flightId' => $this->flightSnapshot->getId(),
            'status'   => $this->flightSnapshot->getStatus()->value,
            'legs'     => LegResource::collection($this->flightSnapshot->getLegs()),
        ];
    }
}

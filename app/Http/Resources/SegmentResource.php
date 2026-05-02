<?php

namespace App\Http\Resources;

use App\Domain\Flight\Snapshots\SegmentSnapshot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SegmentResource extends JsonResource
{
    public function __construct(private readonly SegmentSnapshot $segmentSnapshot)
    {
        parent::__construct($segmentSnapshot);
    }

    public function toArray(Request $request): array
    {
        return [
            'segmentIndex' => $this->segmentSnapshot->getSegmentIndex(),
            'origin'       => $this->segmentSnapshot->getOrigin(),
            'destination'  => $this->segmentSnapshot->getDestination(),
            'departure'    => $this->segmentSnapshot->getDeparture()->format('Y-m-d\TH:i:s'),
            'arrival'      => $this->segmentSnapshot->getArrival()->format('Y-m-d\TH:i:s'),
            'cabinClass'   => $this->segmentSnapshot->getCabinClass()->value,
            'airline'      => $this->segmentSnapshot->getAirlineCode(),
            'flightNumber' => $this->segmentSnapshot->getFlightNumber(),
        ];
    }
}

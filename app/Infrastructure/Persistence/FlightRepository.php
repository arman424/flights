<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Flight\Contracts\FlightRepositoryContract;
use App\Domain\Flight\Flight as FlightAggregate;
use App\Domain\Flight\Snapshots\FlightSnapshot;
use App\Domain\Flight\Snapshots\LegSnapshot;
use App\Domain\Flight\Snapshots\SegmentSnapshot;
use App\Models\Flight as FlightModel;
use Illuminate\Support\Facades\DB;

class FlightRepository implements FlightRepositoryContract
{
    public function save(FlightAggregate $flight): string
    {
        $snapshot = $flight->snapshot();

        return DB::transaction(function () use ($snapshot): string {
            $flightModel = $this->persistFlight($snapshot);

            return $flightModel->id;
        });
    }

    private function persistFlight(FlightSnapshot $flightSnapshot): FlightModel
    {
        /** @var FlightModel $flightModel */
        $flightModel = FlightModel::create([
            'status' => $flightSnapshot->getStatus()->value,
        ]);

        foreach ($flightSnapshot->getLegs() as $leg) {
            $this->persistLeg($flightModel, $leg);
        }

        return $flightModel;
    }

    private function persistLeg(FlightModel $flightModel, LegSnapshot $legSnapshot): void
    {
        $legModel = $flightModel->legs()->create([
            'leg_index' => $legSnapshot->getLegIndex(),
        ]);

        foreach ($legSnapshot->getSegments() as $segment) {
            $this->persistSegment($legModel, $segment);
        }
    }

    private function persistSegment($legModel, SegmentSnapshot $segmentSnapshot): void
    {
        $legModel->segments()->create([
            'segment_index' => $segmentSnapshot->getSegmentIndex(),
            'origin'        => $segmentSnapshot->getOrigin(),
            'destination'   => $segmentSnapshot->getDestination(),
            'departure'     => $segmentSnapshot->getDeparture(),
            'arrival'       => $segmentSnapshot->getArrival(),
            'cabin_class'   => $segmentSnapshot->getCabinClass(),
            'airline_code'  => $segmentSnapshot->getAirlineCode(),
            'flight_number' => $segmentSnapshot->getFlightNumber(),
        ]);
    }
}


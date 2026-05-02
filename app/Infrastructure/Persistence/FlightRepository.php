<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Contracts\UuidGeneratorInterface;
use App\Domain\Flight\Contracts\FlightRepositoryContract;
use App\Domain\Flight\Flight as FlightAggregate;
use App\Domain\Flight\Leg;
use App\Domain\Flight\Segment;
use App\Domain\Flight\Snapshots\LegSnapshot;
use App\Domain\Flight\Snapshots\SegmentSnapshot;
use App\Models\Flight as FlightModel;
use App\Models\Leg as LegModel;
use App\Models\Segment as SegmentModel;
use Illuminate\Support\Facades\DB;

readonly class FlightRepository implements FlightRepositoryContract
{
    public function __construct(
        private UuidGeneratorInterface $uuidGenerator,
    ) {}

    public function get(string $id): FlightAggregate
    {
        /** @var FlightModel $flightModel */
        $flightModel = FlightModel::with('legs.segments')->findOrFail($id);

        $legs = $flightModel->legs
            ->map(fn(LegModel $legModel) => $this->rehydrateLeg($legModel))
            ->all();

        return FlightAggregate::rehydrate($id, $flightModel->status, $legs);
    }

    public function exists(string $id): bool
    {
        return FlightModel::where('id', $id)->exists();
    }

    public function save(FlightAggregate $flight): string
    {
        $snapshot = $flight->snapshot();

        return DB::transaction(function () use ($snapshot): string {
            $flightModel = FlightModel::create([
                'id'     => $snapshot->getId(),
                'status' => $snapshot->getStatus()->value,
            ]);

            $this->bulkInsertLegs($flightModel, $snapshot->getLegs());

            return $snapshot->getId();
        });
    }

    public function update(FlightAggregate $flight): void
    {
        $snapshot = $flight->snapshot();

        DB::transaction(function () use ($snapshot): void {

            $flightModel = FlightModel::where('id', $snapshot->getId())
                ->lockForUpdate()
                ->firstOrFail();

            $flightModel->status = $snapshot->getStatus()->value;
            $flightModel->save();

            foreach ($snapshot->getLegs() as $legSnapshot) {
                $this->updateLeg($flightModel, $legSnapshot);
            }
        });
    }

    /**
     * @param LegSnapshot[] $legSnapshots
     */
    private function bulkInsertLegs(FlightModel $flightModel, array $legSnapshots): void
    {
        foreach ($legSnapshots as $legSnapshot) {
            $legModel = $flightModel->legs()->create([
                'id'        => $this->uuidGenerator->generate(),
                'leg_index' => $legSnapshot->getLegIndex(),
            ]);

            $segments = [];

            foreach ($legSnapshot->getSegments() as $segmentSnapshot) {
                $segments[] = [
                    'id'             => $this->uuidGenerator->generate(),
                    'segment_index'  => $segmentSnapshot->getSegmentIndex(),
                    'origin'         => $segmentSnapshot->getOrigin(),
                    'destination'    => $segmentSnapshot->getDestination(),
                    'departure'      => $segmentSnapshot->getDeparture(),
                    'arrival'        => $segmentSnapshot->getArrival(),
                    'cabin_class'    => $segmentSnapshot->getCabinClass(),
                    'airline_code'   => $segmentSnapshot->getAirlineCode(),
                    'flight_number'  => $segmentSnapshot->getFlightNumber(),
                ];
            }

            $legModel->segments()->createMany($segments);
        }
    }

    private function updateLeg(FlightModel $flightModel, LegSnapshot $legSnapshot): void
    {
        $legModel = $flightModel->legs()
            ->where('leg_index', $legSnapshot->getLegIndex())
            ->firstOrFail();

        foreach ($legSnapshot->getSegments() as $segmentSnapshot) {
            $this->updateSegment($legModel, $segmentSnapshot);
        }
    }

    private function updateSegment(LegModel $legModel, SegmentSnapshot $segmentSnapshot): void
    {
        $legModel->segments()
            ->where('segment_index', $segmentSnapshot->getSegmentIndex())
            ->update([
                'origin'        => $segmentSnapshot->getOrigin(),
                'destination'   => $segmentSnapshot->getDestination(),
                'departure'     => $segmentSnapshot->getDeparture(),
                'arrival'       => $segmentSnapshot->getArrival(),
                'cabin_class'   => $segmentSnapshot->getCabinClass(),
                'airline_code'  => $segmentSnapshot->getAirlineCode(),
                'flight_number' => $segmentSnapshot->getFlightNumber(),
            ]);
    }

    private function rehydrateLeg(LegModel $legModel): Leg
    {
        $segments = $legModel->segments
            ->map(fn(SegmentModel $segmentModel) => $this->rehydrateSegment($segmentModel))
            ->all();

        return Leg::rehydrate($legModel->leg_index, $segments);
    }

    private function rehydrateSegment(SegmentModel $segmentModel): Segment
    {
        return Segment::rehydrate(
            segmentIndex: $segmentModel->segment_index,
            origin:       $segmentModel->origin,
            destination:  $segmentModel->destination,
            departure:    $segmentModel->departure->toDateTimeImmutable(),
            arrival:      $segmentModel->arrival->toDateTimeImmutable(),
            cabinClass:   $segmentModel->cabin_class,
            airlineCode:  $segmentModel->airline_code,
            flightNumber: $segmentModel->flight_number,
        );
    }
}

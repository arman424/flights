<?php

namespace App\Domain\Flight;

use App\Domain\Flight\Snapshots\SegmentSnapshot;
use App\DTO\CreateSegmentDTO;
use DateMalformedStringException;
use DateTimeImmutable;
use DomainException;

/**
 * Segment entity, owned by the Leg (and transitively by the Flight aggregate).
 * State is only accessible through the aggregate's snapshot() method.
 */
final readonly class Segment
{
    private function __construct(
        private int              $segmentIndex,
        private string           $origin,
        private string           $destination,
        private DateTimeImmutable $departure,
        private DateTimeImmutable $arrival,
        private string           $cabinClass,
        private string           $airlineCode,
        private string           $flightNumber,
    ) {}

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public static function create(int $segmentIndex, CreateSegmentDTO $dto): self
    {
        $departure = new DateTimeImmutable($dto->getDeparture());
        $arrival   = new DateTimeImmutable($dto->getArrival());

        if ($arrival <= $departure) {
            throw new DomainException('Segment arrival must be after departure.');
        }

        return new self(
            segmentIndex:  $segmentIndex,
            origin:        strtoupper($dto->getOrigin()),
            destination:   strtoupper($dto->getDestination()),
            departure:     $departure,
            arrival:       $arrival,
            cabinClass:    strtoupper($dto->getCabinClass()),
            airlineCode:   strtoupper($dto->getAirlineCode()),
            flightNumber:  $dto->getFlightNumber(),
        );
    }

    /** @internal called by Leg::toSnapshot() */
    public function toSnapshot(): SegmentSnapshot
    {
        return new SegmentSnapshot(
            segmentIndex:  $this->segmentIndex,
            origin:        $this->origin,
            destination:   $this->destination,
            departure:     $this->departure,
            arrival:       $this->arrival,
            cabinClass:    $this->cabinClass,
            airlineCode:   $this->airlineCode,
            flightNumber:  $this->flightNumber,
        );
    }
}

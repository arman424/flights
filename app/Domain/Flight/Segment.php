<?php

namespace App\Domain\Flight;

use App\Domain\Flight\Snapshots\SegmentSnapshot;
use App\DTO\Flight\CreateSegmentDTO;
use App\DTO\Flight\UpdateSegmentDTO;
use App\Enums\CabinClassCode;
use DateMalformedStringException;
use DateTimeImmutable;
use DomainException;

/**
 * Segment entity, owned by the Leg (and transitively by the Flight aggregate).
 */
final class Segment
{
    private function __construct(
        private int               $segmentIndex,
        private string            $origin,
        private string            $destination,
        private DateTimeImmutable $departure,
        private DateTimeImmutable $arrival,
        private CabinClassCode    $cabinClass,
        private string            $airlineCode,
        private string            $flightNumber,
    ) {}

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public static function create(int $segmentIndex, CreateSegmentDTO $createSegmentDTO): self
    {
        $departure = new DateTimeImmutable($createSegmentDTO->getDeparture());
        $arrival   = new DateTimeImmutable($createSegmentDTO->getArrival());

        if ($arrival <= $departure) {
            throw new DomainException('Segment arrival must be after departure.');
        }

        return new self(
            segmentIndex:  $segmentIndex,
            origin:        strtoupper($createSegmentDTO->getOrigin()),
            destination:   strtoupper($createSegmentDTO->getDestination()),
            departure:     $departure,
            arrival:       $arrival,
            cabinClass:    CabinClassCode::tryFrom($createSegmentDTO->getCabinClass()),
            airlineCode:   strtoupper($createSegmentDTO->getAirlineCode()),
            flightNumber:  $createSegmentDTO->getFlightNumber(),
        );
    }

    /**
     * Create Segment from update DTO
     *
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public static function fromUpdate(int $segmentIndex, UpdateSegmentDTO $updateSegmentDTO): self
    {
        $departure = new DateTimeImmutable($updateSegmentDTO->getDeparture());
        $arrival   = new DateTimeImmutable($updateSegmentDTO->getArrival());

        if ($arrival <= $departure) {
            throw new DomainException('Segment arrival must be after departure.');
        }

        return new self(
            segmentIndex:  $segmentIndex,
            origin:        strtoupper($updateSegmentDTO->getOrigin()),
            destination:   strtoupper($updateSegmentDTO->getDestination()),
            departure:     $departure,
            arrival:       $arrival,
            cabinClass:    CabinClassCode::tryFrom($updateSegmentDTO->getCabinClass()),
            airlineCode:   strtoupper($updateSegmentDTO->getAirlineCode()),
            flightNumber:  $updateSegmentDTO->getFlightNumber(),
        );
    }

    /**
     * Rehydrate Segment from DB.
     */
    public static function rehydrate(
        int               $segmentIndex,
        string            $origin,
        string            $destination,
        DateTimeImmutable $departure,
        DateTimeImmutable $arrival,
        CabinClassCode    $cabinClass,
        string            $airlineCode,
        string            $flightNumber,
    ): self {
        return new self(
            segmentIndex:  $segmentIndex,
            origin:        $origin,
            destination:   $destination,
            departure:     $departure,
            arrival:       $arrival,
            cabinClass:    $cabinClass,
            airlineCode:   $airlineCode,
            flightNumber:  $flightNumber,
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

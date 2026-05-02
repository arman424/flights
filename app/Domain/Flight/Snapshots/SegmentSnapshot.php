<?php

namespace App\Domain\Flight\Snapshots;

use App\Enums\CabinClassCode;
use DateTimeImmutable;

final readonly class SegmentSnapshot
{
    public function __construct(
        private int               $segmentIndex,
        private string            $origin,
        private string            $destination,
        private DateTimeImmutable $departure,
        private DateTimeImmutable $arrival,
        private CabinClassCode    $cabinClass,
        private string            $airlineCode,
        private string            $flightNumber,
    ) {}

    public function getSegmentIndex(): int            { return $this->segmentIndex; }
    public function getOrigin(): string               { return $this->origin; }
    public function getDestination(): string          { return $this->destination; }
    public function getDeparture(): DateTimeImmutable { return $this->departure; }
    public function getArrival(): DateTimeImmutable   { return $this->arrival; }
    public function getCabinClass(): CabinClassCode   { return $this->cabinClass; }
    public function getAirlineCode(): string          { return $this->airlineCode; }
    public function getFlightNumber(): string         { return $this->flightNumber; }
}

<?php

namespace App\DTO\Flight;

final readonly class CreateSegmentDTO
{
    public function __construct(
        private string $origin,
        private string $destination,
        private string $departure,
        private string $arrival,
        private string $cabinClass,
        private string $airlineCode,
        private string $flightNumber,
    ) {}

    public function getOrigin(): string       { return $this->origin; }
    public function getDestination(): string  { return $this->destination; }
    public function getDeparture(): string    { return $this->departure; }
    public function getArrival(): string      { return $this->arrival; }
    public function getCabinClass(): string   { return $this->cabinClass; }
    public function getAirlineCode(): string  { return $this->airlineCode; }
    public function getFlightNumber(): string { return $this->flightNumber; }

    public static function fromArray(array $segment): self
    {
        return new self(
            origin:       $segment['origin'],
            destination:  $segment['destination'],
            departure:    $segment['departure'],
            arrival:      $segment['arrival'],
            cabinClass:   $segment['cabinClass'],
            airlineCode:  $segment['airline'],
            flightNumber: $segment['flightNumber'],
        );
    }
}


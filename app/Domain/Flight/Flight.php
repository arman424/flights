<?php

namespace App\Domain\Flight;

use App\Domain\Flight\Snapshots\FlightSnapshot;
use App\DTO\CreateFlightDTO;
use App\DTO\CreateLegDTO;
use App\Enums\FlightStatus;
use DateMalformedStringException;
use DomainException;

/**
 * Flight Aggregate Root.
 */
final readonly class Flight
{
    private function __construct(private array $legs) {}

    /**
     * The only entry point for creating a Flight.
     *
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public static function create(CreateFlightDTO $createFlightDTO): self
    {
        if (empty($createFlightDTO->getLegs())) {
            throw new DomainException('A flight must have at least one leg.');
        }

        $legs = array_map(
            fn(CreateLegDTO $legDTO, int $legIndex) => Leg::create($legIndex, $legDTO),
            $createFlightDTO->getLegs(),
            array_keys($createFlightDTO->getLegs()),
        );

        return new self(legs: $legs);
    }

    /**
     * Returns a complete, immutable snapshot of the aggregate's state.
     */
    public function snapshot(): FlightSnapshot
    {
        return new FlightSnapshot(
            status: FlightStatus::Scheduled,
            legs:   array_map(fn(Leg $leg) => $leg->toSnapshot(), $this->legs),
        );
    }
}

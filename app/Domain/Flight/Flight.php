<?php

namespace App\Domain\Flight;

use App\Domain\Contracts\UuidGeneratorInterface;
use App\Domain\Flight\Snapshots\FlightSnapshot;
use App\DTO\Flight\CreateFlightDTO;
use App\DTO\Flight\CreateLegDTO;
use App\DTO\Flight\UpdateFlightDTO;
use App\DTO\Flight\UpdateLegDTO;
use App\Enums\FlightStatus;
use App\Enums\LegType;
use DateMalformedStringException;
use DomainException;

/**
 * Flight Aggregate Root.
 */
final class Flight
{
    private function __construct(
        private string       $id,
        private FlightStatus $status,
        private array        $legs,
    ) {}

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public static function create(CreateFlightDTO $createFlightDTO, UuidGeneratorInterface $uuidGenerator): self
    {
        if (empty($createFlightDTO->getLegs())) {
            throw new DomainException('A flight must have at least one leg.');
        }

        $legCases = LegType::cases();

        if (count($createFlightDTO->getLegs()) > count($legCases)) {
            throw new DomainException('A flight cannot have more than ' . count($legCases) . ' legs.');
        }

        $legs = array_map(
            fn(CreateLegDTO $legDTO, int $index) => Leg::create($legCases[$index], $legDTO),
            $createFlightDTO->getLegs(),
            array_keys($createFlightDTO->getLegs()),
        );

        return new self(id: $uuidGenerator->generate(), status: FlightStatus::Scheduled, legs: $legs);
    }

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public function update(UpdateFlightDTO $updateFlightDTO): void
    {
        if (empty($updateFlightDTO->getLegs())) {
            throw new DomainException('At least one leg must be provided for an update.');
        }

        $legCases = LegType::cases();

        $this->legs = array_map(
            fn(UpdateLegDTO $legDTO, int $index) => Leg::update($legCases[$index], $legDTO),
            $updateFlightDTO->getLegs(),
            array_keys($updateFlightDTO->getLegs()),
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param Leg[] $legs
     */
    public static function rehydrate(string $id, FlightStatus $status, array $legs): self
    {
        return new self(id: $id, status: $status, legs: $legs);
    }

    public function toSnapshot(): FlightSnapshot
    {
        return new FlightSnapshot(
            id:     $this->id,
            status: $this->status,
            legs:   array_map(fn(Leg $leg) => $leg->toSnapshot(), $this->legs),
        );
    }
}

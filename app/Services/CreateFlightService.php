<?php

namespace App\Services;

use App\Domain\Flight\Contracts\FlightRepositoryContract;
use App\Domain\Contracts\UuidGeneratorInterface;
use App\Domain\Flight\Flight;
use App\DTO\Flight\CreateFlightDTO;
use DateMalformedStringException;
use DomainException;

/**
 * Application service – orchestrates the "create a flight" use case.
 */
final readonly class CreateFlightService
{
    public function __construct(
        private FlightRepositoryContract $flightRepository,
        private UuidGeneratorInterface   $uuidGenerator,
    ) {}

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public function execute(CreateFlightDTO $createFlightDTO): string
    {
        $flight = Flight::create($createFlightDTO, $this->uuidGenerator);

        return $this->flightRepository->save($flight);
    }
}

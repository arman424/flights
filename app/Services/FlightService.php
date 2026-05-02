<?php

namespace App\Services;

use App\Domain\Flight\Contracts\FlightRepositoryContract;
use App\Domain\Flight\Flight;
use App\DTO\CreateFlightDTO;
use DateMalformedStringException;
use DomainException;

/**
 * Application service – orchestrates the "create a flight" use case.
 */
readonly class FlightService
{
    public function __construct(
        private FlightRepositoryContract $flightRepository,
    ) {}

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public function createFlight(CreateFlightDTO $createFlightDTO): string
    {
        $flight = Flight::create($createFlightDTO);

        return $this->flightRepository->save($flight);
    }
}

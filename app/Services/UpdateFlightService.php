<?php

namespace App\Services;

use App\Domain\Flight\Contracts\FlightRepositoryContract;
use App\DTO\Flight\UpdateFlightDTO;
use DateMalformedStringException;
use DomainException;

final readonly class UpdateFlightService
{
    public function __construct(
        private FlightRepositoryContract $flightRepository,
    ) {}

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public function execute(UpdateFlightDTO $updateFlightDTO): void
    {
        $flight = $this->flightRepository->get($updateFlightDTO->getFlightId());

        $flight->update($updateFlightDTO);

        // TODO: Consider applying the Transactional Outbox Pattern
        // by dispatching a FlightUpdated event inside the same transaction
        // to reliably propagate changes and keep a history of flight updates.
        $this->flightRepository->update($flight);
    }
}

<?php

namespace App\Services;

use App\Domain\Flight\Contracts\FlightRepositoryContract;
use App\Domain\Flight\Flight;

final readonly class GetFlightService
{
    public function __construct(
        private FlightRepositoryContract $flightRepository,
    ) {}

    public function execute(string $flightId): Flight
    {
        return $this->flightRepository->get($flightId);
    }
}

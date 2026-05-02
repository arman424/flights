<?php

namespace App\Services;

use App\Domain\Flight\Contracts\FlightRepositoryContract;
use App\DTO\Flight\UpdateFlightDTO;
use App\Jobs\UpdateFlightJob;
use App\Services\Contracts\IdempotencyGuardContract;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class ScheduleFlightUpdateService
{
    public function __construct(
        private IdempotencyGuardContract $idempotencyGuard,
        private FlightRepositoryContract $flightRepository,
    ) {}

    public function execute(string $idempotencyKey, UpdateFlightDTO $updateFlightDTO): void
    {
        if (! $this->flightRepository->exists($updateFlightDTO->getFlightId())) {
            throw new NotFoundHttpException('Flight not found.');
        }

        if ($this->idempotencyGuard->isDone($idempotencyKey)) {
            throw new ConflictHttpException('This idempotency key has already been used. Use a new key for a new request.');
        }

        if (! $this->idempotencyGuard->reserve($idempotencyKey)) {
            throw new ConflictHttpException('Request with this idempotency key is already being processed.');
        }

        UpdateFlightJob::dispatch($updateFlightDTO, $idempotencyKey);
    }
}

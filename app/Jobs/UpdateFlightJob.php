<?php

namespace App\Jobs;

use App\DTO\Flight\UpdateFlightDTO;
use App\Services\Contracts\IdempotencyGuardContract;
use App\Services\UpdateFlightService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

final class UpdateFlightJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public function __construct(
        private readonly UpdateFlightDTO $updateFlightDTO,
        private readonly string $idempotencyKey,
    ) {
        $this->onQueue('flight-updates');
    }

    public function uniqueId(): string
    {
        return $this->idempotencyKey;
    }

    public function handle(
        UpdateFlightService $updateFlightService,
        IdempotencyGuardContract $idempotencyGuard
    ): void
    {
        try {
            $updateFlightService->execute($this->updateFlightDTO);
            $idempotencyGuard->markDone($this->idempotencyKey);
        } catch (Throwable $e) {
            throw $e;
        }
    }

    public function failed(IdempotencyGuardContract $idempotencyGuard): void
    {
        $idempotencyGuard->reset($this->idempotencyKey);
    }
}

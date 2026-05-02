<?php

namespace App\Domain\Flight\Snapshots;

use App\Enums\FlightStatus;

final readonly class FlightSnapshot
{
    /**
     * @param LegSnapshot[] $legs
     */
    public function __construct(
        private string       $id,
        private FlightStatus $status,
        private array        $legs,
    ) {}

    public function getId(): string { return $this->id; }

    public function getStatus(): FlightStatus { return $this->status; }

    /** @return LegSnapshot[] */
    public function getLegs(): array { return $this->legs; }
}

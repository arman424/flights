<?php

namespace App\DTO\Flight;

final readonly class UpdateFlightDTO
{
    /**
     * @param UpdateLegDTO[] $legs
     */
    public function __construct(
        private string $flightId,
        private array  $legs,
    ) {}

    public function getFlightId(): string { return $this->flightId; }

    /** @return UpdateLegDTO[] */
    public function getLegs(): array { return $this->legs; }

    public static function fromValidated(string $flightId, array $validated): self
    {
        return new self(
            flightId: $flightId,
            legs: array_map(
                fn(array $leg) => UpdateLegDTO::fromArray($leg),
                $validated['legs'],
            ),
        );
    }
}


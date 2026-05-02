<?php

namespace App\DTO;

final readonly class CreateFlightDTO
{
    /**
     * @param CreateLegDTO[] $legs
     */
    public function __construct(
        private array $legs,
    ) {}

    /** @return CreateLegDTO[] */
    public function getLegs(): array { return $this->legs; }

    public static function fromValidated(array $validated): self
    {
        return new self(
            legs: array_map(
                fn(array $leg) => CreateLegDTO::fromArray($leg),
                $validated['legs'],
            ),
        );
    }
}

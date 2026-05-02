<?php

namespace App\DTO\Flight;

final readonly class UpdateLegDTO
{
    /**
     * @param UpdateSegmentDTO[] $segments
     */
    public function __construct(private array $segments) {}

    /** @return UpdateSegmentDTO[] */
    public function getSegments(): array { return $this->segments; }

    public static function fromArray(array $leg): self
    {
        return new self(
            segments: array_map(
                fn(array $segment) => UpdateSegmentDTO::fromArray($segment),
                $leg['segments'],
            ),
        );
    }
}


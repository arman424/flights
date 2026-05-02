<?php

namespace App\DTO\Flight;

final readonly class CreateLegDTO
{
    /**
     * @param CreateSegmentDTO[] $segments
     */
    public function __construct(private array $segments) {}

    /** @return CreateSegmentDTO[] */
    public function getSegments(): array { return $this->segments; }

    public static function fromArray(array $leg): self
    {
        return new self(
            segments: array_map(
                fn(array $segment) => CreateSegmentDTO::fromArray($segment),
                $leg['segments'],
            ),
        );
    }
}


<?php

namespace App\DTO;

final readonly class CreateLegDTO
{
    /**
     * @param CreateSegmentDTO[] $segments
     */
    public function __construct(
        private array $segments,
    ) {}

    /** @return CreateSegmentDTO[] */
    public function getSegments(): array { return $this->segments; }

    public static function fromArray(array $legs): self
    {
        return new self(
            segments: array_map(
                fn(array $segment) => CreateSegmentDTO::fromArray($segment),
                $legs['segments'],
            ),
        );
    }
}

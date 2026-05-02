<?php

namespace App\Domain\Flight\Snapshots;

final readonly class LegSnapshot
{
    /**
     * @param SegmentSnapshot[] $segments
     */
    public function __construct(
        private int   $legIndex,
        private array $segments,
    ) {}

    public function getLegIndex(): int { return $this->legIndex; }

    /** @return SegmentSnapshot[] */
    public function getSegments(): array { return $this->segments; }
}

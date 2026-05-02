<?php

namespace App\Domain\Flight\Snapshots;

use App\Enums\LegType;

final readonly class LegSnapshot
{
    /**
     * @param SegmentSnapshot[] $segments
     */
    public function __construct(
        private LegType $legIndex,
        private array    $segments,
    ) {}

    public function getLegType(): LegType { return $this->legIndex; }

    /** @return SegmentSnapshot[] */
    public function getSegments(): array { return $this->segments; }
}

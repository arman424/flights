<?php

namespace App\Domain\Flight;

use App\Domain\Flight\Snapshots\LegSnapshot;
use App\DTO\Flight\CreateLegDTO;
use App\DTO\Flight\CreateSegmentDTO;
use App\DTO\Flight\UpdateLegDTO;
use App\DTO\Flight\UpdateSegmentDTO;
use DateMalformedStringException;
use DomainException;

/**
 * Leg entity, owned by the Flight aggregate.
 */
final class Leg
{
    private function __construct(
        private int   $legIndex,
        private array $segments,
    ) {}

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public static function create(int $legIndex, CreateLegDTO $createLegDTO): self
    {
        if (empty($createLegDTO->getSegments())) {
            throw new DomainException("Leg $legIndex must have at least one segment.");
        }

        $segments = array_map(
            fn(CreateSegmentDTO $segmentDTO, int $segmentIndex) => Segment::create($segmentIndex, $segmentDTO),
            $createLegDTO->getSegments(),
            array_keys($createLegDTO->getSegments()),
        );

        return new self(legIndex: $legIndex, segments: $segments);
    }

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public static function fromUpdate(int $legIndex, UpdateLegDTO $updateLegDTO): self
    {
        if (empty($updateLegDTO->getSegments())) {
            throw new DomainException("Leg $legIndex must have at least one segment.");
        }

        $segments = array_map(
            fn(UpdateSegmentDTO $segmentDTO, int $segmentIndex) => Segment::fromUpdate($segmentIndex, $segmentDTO),
            $updateLegDTO->getSegments(),
            array_keys($updateLegDTO->getSegments()),
        );

        return new self(legIndex: $legIndex, segments: $segments);
    }

    /**
     * @param Segment[] $segments
     */
    public static function rehydrate(int $legIndex, array $segments): self
    {
        return new self(legIndex: $legIndex, segments: $segments);
    }

    /** @internal called by Flight::snapshot() */
    public function toSnapshot(): LegSnapshot
    {
        return new LegSnapshot(
            legIndex: $this->legIndex,
            segments: array_map(fn(Segment $segment) => $segment->toSnapshot(), $this->segments),
        );
    }
}

<?php

namespace App\Domain\Flight;

use App\Domain\Flight\Snapshots\LegSnapshot;
use App\DTO\CreateLegDTO;
use App\DTO\CreateSegmentDTO;
use DateMalformedStringException;
use DomainException;

/**
 * Leg entity, owned by the Flight aggregate.
 * State is only accessible through the aggregate's snapshot() method.
 */
final readonly class Leg
{
    private function __construct(private int $legIndex, private array $segments) {}

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

        return new self(
            legIndex: $legIndex,
            segments: $segments,
        );
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

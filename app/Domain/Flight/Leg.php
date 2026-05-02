<?php

namespace App\Domain\Flight;

use App\Domain\Flight\Snapshots\LegSnapshot;
use App\DTO\Flight\CreateLegDTO;
use App\DTO\Flight\CreateSegmentDTO;
use App\DTO\Flight\UpdateLegDTO;
use App\DTO\Flight\UpdateSegmentDTO;
use App\Enums\LegType;
use DateMalformedStringException;
use DomainException;

/**
 * Leg entity, owned by the Flight aggregate.
 */
final class Leg
{
    private function __construct(
        private LegType $legType,
        private array   $segments,
    ) {}

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public static function create(LegType $legType, CreateLegDTO $createLegDTO): self
    {
        if (empty($createLegDTO->getSegments())) {
            throw new DomainException("Leg $legType->name must have at least one segment.");
        }

        $segments = array_map(
            fn(CreateSegmentDTO $segmentDTO, int $segmentIndex) => Segment::create($segmentIndex, $segmentDTO),
            $createLegDTO->getSegments(),
            array_keys($createLegDTO->getSegments()),
        );

        return new self(legType: $legType, segments: $segments);
    }

    /**
     * @throws DomainException
     * @throws DateMalformedStringException
     */
    public static function update(LegType $legType, UpdateLegDTO $updateLegDTO): self
    {
        if (empty($updateLegDTO->getSegments())) {
            throw new DomainException("Leg $legType->name must have at least one segment.");
        }

        $segments = array_map(
            fn(UpdateSegmentDTO $segmentDTO, int $segmentIndex) => Segment::update($segmentIndex, $segmentDTO),
            $updateLegDTO->getSegments(),
            array_keys($updateLegDTO->getSegments()),
        );

        return new self(legType: $legType, segments: $segments);
    }

    /**
     * @param Segment[] $segments
     */
    public static function rehydrate(LegType $legType, array $segments): self
    {
        return new self(legType: $legType, segments: $segments);
    }

    /** @internal called by Flight::toSnapshot() */
    public function toSnapshot(): LegSnapshot
    {
        return new LegSnapshot(
            legType: $this->legType,
            segments: array_map(fn(Segment $segment) => $segment->toSnapshot(), $this->segments),
        );
    }
}

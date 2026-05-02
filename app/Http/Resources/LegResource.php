<?php

namespace App\Http\Resources;

use App\Domain\Flight\Snapshots\LegSnapshot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LegResource extends JsonResource
{
    public function __construct(private readonly LegSnapshot $legSnapshot)
    {
        parent::__construct($legSnapshot);
    }

    public function toArray(Request $request): array
    {
        return [
            'legIndex' => $this->legSnapshot->getLegIndex(),
            'segments' => SegmentResource::collection($this->legSnapshot->getSegments()),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFlightRequest;
use App\Http\Requests\UpdateFlightRequest;
use App\Http\Resources\FlightResource;
use App\Services\CreateFlightService;
use App\Services\GetFlightService;
use App\Services\ScheduleFlightUpdateService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FlightController extends Controller
{
    public function __construct(
        private readonly GetFlightService            $getFlightService,
        private readonly CreateFlightService         $createFlightService,
        private readonly ScheduleFlightUpdateService $scheduleFlightUpdateService,
    ) {}

    public function get(string $flightId): FlightResource
    {
        $flight = $this->getFlightService->execute($flightId);

        return new FlightResource($flight->toSnapshot());
    }

    public function store(CreateFlightRequest $request): JsonResponse
    {
        $flightId = $this->createFlightService->execute($request->toDto());

        return response()->json(['flightId' => $flightId], Response::HTTP_CREATED);
    }

    public function update(UpdateFlightRequest $request, string $flightId): Response
    {
        $this->scheduleFlightUpdateService->execute(
            $request->validated('idempotency_key'),
            $request->toDto($flightId),
        );

        return response()->noContent();
    }
}

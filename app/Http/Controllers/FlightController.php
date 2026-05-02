<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFlightRequest;
use App\Services\FlightService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FlightController extends Controller
{
    public function __construct(private readonly FlightService $flightService) {}

    public function store(CreateFlightRequest $request): JsonResponse
    {
        $flightId = $this->flightService->createFlight($request->toDto());

        return response()->json(
            ['flightId' => $flightId],
            Response::HTTP_CREATED,
        );
    }
}


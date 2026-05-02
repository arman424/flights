<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     * Validates the Api-Key header against the configured secret.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('app.api_key');

        if (empty($apiKey) || $request->header('Api-Key') !== $apiKey) {
            return response()->json([
                'message' => 'Unauthorized. Invalid or missing Api-Key header.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}


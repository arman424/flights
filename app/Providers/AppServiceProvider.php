<?php

namespace App\Providers;

use App\Domain\Contracts\UuidGeneratorInterface;
use App\Domain\Flight\Contracts\FlightRepositoryContract;
use App\Infrastructure\Persistence\FlightRepository;
use App\Infrastructure\UuidGenerator;
use App\Services\Contracts\IdempotencyGuardContract;
use App\Services\IdempotencyGuard;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FlightRepositoryContract::class, FlightRepository::class);
        $this->app->bind(UuidGeneratorInterface::class, UuidGenerator::class);
        $this->app->bind(IdempotencyGuardContract::class, IdempotencyGuard::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Horizon::auth(function ($request) {
            return app()->environment('local');
        });
    }
}

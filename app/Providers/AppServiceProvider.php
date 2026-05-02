<?php

namespace App\Providers;

use App\Domain\Flight\Contracts\FlightRepositoryContract;
use App\Infrastructure\Persistence\FlightRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the domain interface to the Eloquent implementation.
        // Swap this binding (e.g. in tests) without touching any domain or application code.
        $this->app->bind(FlightRepositoryContract::class, FlightRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Services;

use App\Services\Contracts\IdempotencyGuardContract;
use Illuminate\Support\Facades\Cache;

final class IdempotencyGuard implements IdempotencyGuardContract
{
    private const TTL_HOURS = 24;

    public function reserve(string $key): bool
    {
        return Cache::add(
            $this->cacheKey($key),
            'processing',
            now()->addHours(self::TTL_HOURS)
        );
    }

    public function markDone(string $key): void
    {
        Cache::put(
            $this->cacheKey($key),
            'done',
            now()->addHours(self::TTL_HOURS)
        );
    }

    public function isDone(string $key): bool
    {
        return Cache::get($this->cacheKey($key)) === 'done';
    }

    public function reset(string $key): void
    {
        Cache::forget($this->cacheKey($key));
    }

    private function cacheKey(string $key): string
    {
        return "flight-key:$key";
    }
}

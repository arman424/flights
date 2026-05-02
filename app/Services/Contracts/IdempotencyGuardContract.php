<?php

namespace App\Services\Contracts;

interface IdempotencyGuardContract
{
    public function reserve(string $key): bool;

    public function markDone(string $key): void;

    public function isDone(string $key): bool;

    public function reset(string $key): void;
}

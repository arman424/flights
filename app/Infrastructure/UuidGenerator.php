<?php

namespace App\Infrastructure;

use App\Domain\Contracts\UuidGeneratorInterface;
use Illuminate\Support\Str;

final class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): string
    {
        return (string) Str::uuid();
    }
}

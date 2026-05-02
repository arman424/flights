<?php

namespace App\Domain\Contracts;

interface UuidGeneratorInterface
{
    public function generate(): string;
}

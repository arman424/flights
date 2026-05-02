<?php

namespace App\Domain\Flight\Contracts;

use App\Domain\Flight\Flight;

interface FlightRepositoryContract
{
    public function get(string $id): Flight;

    public function exists(string $id): bool;

    public function save(Flight $flight): string;

    public function update(Flight $flight): void;
}

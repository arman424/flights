<?php

namespace App\Domain\Flight\Contracts;

use App\Domain\Flight\Flight;

interface FlightRepositoryContract
{
    public function save(Flight $flight): string;
}

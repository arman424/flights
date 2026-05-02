<?php

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    /**
     * A representative set of IATA airline codes.
     * Extend this list as needed.
     */
    private const AIRLINES = [
        'AA' => 'American Airlines',
        'BA' => 'British Airways',
        'DL' => 'Delta Air Lines',
        'EK' => 'Emirates',
        'IB' => 'Iberia',
        'KL' => 'KLM Royal Dutch Airlines',
        'LH' => 'Lufthansa',
        'QR' => 'Qatar Airways',
        'TK' => 'Turkish Airlines',
        'UA' => 'United Airlines',
        'VY' => 'Vueling',
    ];

    public function run(): void
    {
        foreach (self::AIRLINES as $code => $name) {
            Airline::firstOrCreate(
                ['code' => $code],
                ['name' => $name],
            );
        }
    }
}


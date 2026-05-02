<?php

namespace Database\Seeders;

use App\Enums\CabinClassCode;
use App\Models\CabinClass;
use Illuminate\Database\Seeder;

class CabinClassSeeder extends Seeder
{
    public function run(): void
    {
        foreach (CabinClassCode::cases() as $case) {
            CabinClass::firstOrCreate(
                ['code' => $case->value],
                ['name' => $case->label()],
            );
        }
    }
}


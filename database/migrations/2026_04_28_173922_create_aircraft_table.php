<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aircraft', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('airline_code');
            $table->string('aircraft_type'); // e.g. "Boeing 777", "Airbus A380"
            $table->string('flight_number'); // e.g. "UA101"
            $table->string('registration')->nullable(); // Tail number
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('airline_code')
                ->references('code')
                ->on('airlines');

            $table->index(['airline_code', 'flight_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aircraft');
    }
};


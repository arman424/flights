<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('segments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('leg_id');
            $table->unsignedInteger('segment_index'); // Position within the leg
            $table->string('origin', 3);              // IATA airport code
            $table->string('destination', 3);         // IATA airport code
            $table->dateTime('departure');
            $table->dateTime('arrival');
            $table->string('cabin_class');
            $table->string('airline_code');
            $table->string('flight_number');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('leg_id')
                ->references('id')
                ->on('legs')
                ->onDelete('cascade');

            $table->foreign('cabin_class')
                ->references('code')
                ->on('cabin_classes');

            $table->foreign('airline_code')
                ->references('code')
                ->on('airlines');

            $table->unique(['leg_id', 'segment_index']);
            $table->index('origin');
            $table->index('destination');
            $table->index('departure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segments');
    }
};

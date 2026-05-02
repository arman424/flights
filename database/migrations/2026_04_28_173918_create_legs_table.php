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
        Schema::create('legs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('flight_id');
            $table->unsignedInteger('leg_index'); // 0 = outbound, 1 = return, etc.
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('flight_id')
                ->references('id')
                ->on('flights')
                ->onDelete('cascade');

            $table->unique(['flight_id', 'leg_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legs');
    }
};

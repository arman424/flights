<?php

use App\Enums\LegType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('flight_id');
            $table->enum('leg_index', array_column(LegType::cases(), 'value'));
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('flight_id')
                ->references('id')
                ->on('flights')
                ->onDelete('cascade');

            $table->unique(['flight_id', 'leg_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legs');
    }
};

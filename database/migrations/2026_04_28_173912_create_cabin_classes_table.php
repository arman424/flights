<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabin_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique(); // Y, J, F, W  – plain string, NOT a DB enum
            $table->string('name');           // Economy, Business, First, Premium Economy
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabin_classes');
    }
};


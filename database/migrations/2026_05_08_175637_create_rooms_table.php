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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('room_type');
            $table->integer('capacity');
            $table->decimal('price_per_night', 8, 2);
            $table->string('room_number')->unique();
            $table->boolean('is_available')->default(true);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('amenities')->nullable();
            $table->integer('floor')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

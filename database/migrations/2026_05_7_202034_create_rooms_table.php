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
            $table->string('room_no')->unique();
            $table->enum('room_type', [
                'Standard Room',
                'Standard Premium Room',
                'Family Room',
            ]);
            $table->unsignedTinyInteger('floor');    // 1, 2, 4
            $table->decimal('base_price', 10, 2);   // 1500, 1900, 2700
            $table->enum('status', [
                'available',
                'occupied',
                'maintenance',
                'reserved',
            ])->default('available');
            $table->timestamps();
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

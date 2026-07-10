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
        Schema::create('walk_in_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms');
            $table->string('fullname', 255);
            $table->string('phone_number', 20);
            $table->string('room_type');
            $table->string('floor_level');
            $table->string('ambiance');
            $table->string('food_package');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('number_of_guests');
            $table->decimal('room_price', 10, 2);
            $table->decimal('micro_pricing_amount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['Confirmed', 'Checked In', 'Completed', 'Cancelled', 'Archived'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walk_in_bookings');
    }
};

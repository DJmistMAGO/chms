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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('reference_number')->unique();
            $table->string('room_type');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('number_of_guests');
            $table->decimal('room_price', 10, 2);
            $table->decimal('micro_pricing_amount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', [ 'pending', 'verified', 'confirmed', 'expired', 'cancelled'])->default('pending');
            $table->timestamp('expires_at');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->text('special_requests')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('rejects guests over room capacity and unsupported booking options', function () {
    $user = User::factory()->create(['valid_id' => 'valid-ids/client-id.jpg']);
    $checkIn = now()->addDays(10)->toDateString();

    $response = $this->actingAs($user)->postJson(route('reservations.booking.store'), [
        'room_type' => 'Family Room',
        'room_type_slug' => 'family',
        'check_in' => $checkIn,
        'check_out' => now()->addDays(12)->toDateString(),
        'number_of_guests' => 7,
        'nights' => 2,
        'floor_level' => 'Floor 1',
        'ambiance' => 'Unlisted ambiance',
        'food_package' => 'No Food',
        'room_price' => 2700,
        'micro_pricing_amount' => 0,
        'total_price' => 5400,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['number_of_guests', 'ambiance']);
});

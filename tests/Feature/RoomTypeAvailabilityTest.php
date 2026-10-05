<?php

use App\Mail\StatusEmail;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

function createRoomForAvailabilityTest(string $roomNumber): void
{
    Room::create([
        'room_no' => $roomNumber,
        'room_type' => 'Standard Room',
        'floor' => 1,
        'base_price' => 1500,
        'status' => 'Available',
    ]);
}

function roomAvailabilityBookingPayload(string $checkIn, string $checkOut): array
{
    return [
        'room_type' => 'Standard Room',
        'room_type_slug' => 'standard',
        'check_in' => $checkIn,
        'check_out' => $checkOut,
        'number_of_guests' => 1,
        'nights' => 1,
        'floor_level' => 'Floor 1',
        'ambiance' => 'Regular Room',
        'food_package' => 'No Food',
        'room_price' => 1500,
        'micro_pricing_amount' => 0,
        'total_price' => 1500,
    ];
}

it('rejects a booking when every room of the selected type is occupied for the stay', function () {
    createRoomForAvailabilityTest('101');

    $checkIn = now()->addDays(10)->toDateString();
    $checkOut = now()->addDays(11)->toDateString();

    Booking::factory()->create([
        'room_type' => 'Standard Room',
        'check_in' => $checkIn,
        'check_out' => $checkOut,
        'status' => 'pending',
    ]);

    $response = $this->actingAs(User::factory()->create())
        ->from('/booking/new/standard')
        ->post(route('booking.new.store'), roomAvailabilityBookingPayload($checkIn, $checkOut));

    $response->assertSessionHasErrors('check_in');
    expect(Booking::where('room_type', 'Standard Room')->count())->toBe(1);
});

it('allows a booking when at least one room of the selected type remains available', function () {
    Mail::fake();
    createRoomForAvailabilityTest('101');
    createRoomForAvailabilityTest('102');

    $checkIn = now()->addDays(10)->toDateString();
    $checkOut = now()->addDays(11)->toDateString();

    Booking::factory()->create([
        'room_type' => 'Standard Room',
        'check_in' => $checkIn,
        'check_out' => $checkOut,
        'status' => 'pending',
    ]);

    $response = $this->actingAs(User::factory()->create())
        ->post(route('booking.new.store'), roomAvailabilityBookingPayload($checkIn, $checkOut));

    $response->assertRedirect();
    expect(Booking::where('room_type', 'Standard Room')->count())->toBe(2);
    Mail::assertSent(StatusEmail::class);
});

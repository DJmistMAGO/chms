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
        'floor_level' => 'Floor 1',
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
        'floor_level' => 'Floor 1',
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

it('only marks floors with inventory for the selected room type as available', function () {
    Room::create([
        'room_no' => 'S101',
        'room_type' => 'Standard Room',
        'floor' => 1,
        'base_price' => 1500,
        'status' => 'Available',
    ]);
    Room::create([
        'room_no' => 'F201',
        'room_type' => 'Family Room',
        'floor' => 2,
        'base_price' => 2700,
        'status' => 'Available',
    ]);

    $checkIn = now()->addDays(10)->toDateString();
    $checkOut = now()->addDays(12)->toDateString();
    $response = $this->getJson(
        route('booking.check-floors', 'family') . '?' . http_build_query([
            'check_in' => $checkIn,
            'check_out' => $checkOut,
        ])
    );

    $response->assertOk();
    $availability = $response->json('floors');
    expect($availability['Floor 1']['available'])->toBeFalse()
        ->and($availability['Floor 2']['available'])->toBeTrue()
        ->and($availability['Floor 4']['available'])->toBeFalse();

    Booking::factory()->create([
        'room_type' => 'Family Room',
        'floor_level' => 'Floor 2',
        'check_in' => $checkIn,
        'check_out' => $checkOut,
        'status' => 'pending',
    ]);
    $bookedFloorAvailability = $this->getJson(
        route('booking.check-floors', 'family') . '?' . http_build_query([
            'check_in' => $checkIn,
            'check_out' => $checkOut,
        ])
    )->assertOk()->json('floors');
    expect($bookedFloorAvailability['Floor 2']['fully_booked'])->toBeTrue()
        ->and($bookedFloorAvailability['Floor 2']['available'])->toBeFalse();

    $this->get(route('customize.booking', 'family'))
        ->assertOk()
        ->assertSee('Unavailable for this room type');

    $user = User::factory()->create(['valid_id' => 'valid-ids/client-id.jpg']);
    $this->actingAs($user)
        ->get(route('booking.new.wizard', 'family'))
        ->assertOk()
        ->assertSee('Unavailable for this room type');

    $this->actingAs($user)
        ->get(route('reservations.booking.wizard', 'family'))
        ->assertOk()
        ->assertSee('Unavailable for this room type');
});

it('rejects a booking on a floor without rooms of the requested type', function () {
    Room::create([
        'room_no' => 'F201',
        'room_type' => 'Family Room',
        'floor' => 2,
        'base_price' => 2700,
        'status' => 'Available',
    ]);

    $checkIn = now()->addDays(10)->toDateString();
    $payload = [
        'room_type' => 'Family Room',
        'room_type_slug' => 'family',
        'check_in' => $checkIn,
        'check_out' => now()->addDays(11)->toDateString(),
        'number_of_guests' => 1,
        'nights' => 1,
        'floor_level' => 'Floor 1',
        'ambiance' => 'Regular Room',
        'food_package' => 'No Food',
        'room_price' => 2700,
        'micro_pricing_amount' => 0,
        'total_price' => 2700,
    ];

    $this->actingAs(User::factory()->create())
        ->post(route('booking.new.store'), $payload)
        ->assertSessionHasErrors('check_in');

    expect(Booking::where('room_type', 'Family Room')->count())->toBe(0);
});

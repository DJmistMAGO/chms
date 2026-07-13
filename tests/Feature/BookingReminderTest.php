<?php

use App\Mail\BookingExpirationWarning;
use App\Models\Booking;
use App\Models\BookingReminder;
use Illuminate\Support\Facades\Mail;


it('sends the 12 hour warning exactly once for a booking inside that window', function () {
    Mail::fake();

    $booking = Booking::factory()->create([
        'status' => 'pending',
        'expires_at' => now()->addHours(11)->addMinutes(30), // inside 0-12h window
    ]);

    $this->artisan('booking:reminders')->assertExitCode(0);

    Mail::assertQueued(BookingExpirationWarning::class, function ($mail) use ($booking) {
        return $mail->booking->is($booking) && $mail->remainingTime === '12 hours';
    });

    expect(BookingReminder::where('booking_id', $booking->id)->where('type', '12h')->exists())->toBeTrue();

    // Running the command again should NOT resend, because a reminder row now exists.
    Mail::fake();
    $this->artisan('booking:reminders');
    Mail::assertNotQueued(BookingExpirationWarning::class);
});

it('sends the correct stage warning based on how close expiry is', function () {
    Mail::fake();

    $booking = Booking::factory()->create([
        'status' => 'pending',
        'expires_at' => now()->addMinutes(45), // inside the 1h window only
    ]);

    // Simulate earlier stages already having been sent.
    BookingReminder::create(['booking_id' => $booking->id, 'type' => '12h', 'sent_at' => now()->subHours(11)]);
    BookingReminder::create(['booking_id' => $booking->id, 'type' => '3h', 'sent_at' => now()->subHours(2)]);

    $this->artisan('booking:reminders');

    Mail::assertQueued(BookingExpirationWarning::class, function ($mail) use ($booking) {
        return $mail->booking->is($booking) && $mail->remainingTime === '1 hour';
    });

    expect(BookingReminder::where('booking_id', $booking->id)->where('type', '1h')->exists())->toBeTrue();
});

it('does not warn bookings that are not pending', function () {
    Mail::fake();

    Booking::factory()->create([
        'status' => 'confirmed',
        'expires_at' => now()->addHours(1),
    ]);

    $this->artisan('booking:reminders');

    Mail::assertNothingQueued();
});

it('skips bookings whose expiry is outside every warning window', function () {
    Mail::fake();

    Booking::factory()->create([
        'status' => 'pending',
        'expires_at' => now()->addDays(2),
    ]);

    $this->artisan('booking:reminders');

    Mail::assertNothingQueued();
});

it('does not warn a booking that already expired', function () {
    Mail::fake();

    Booking::factory()->create([
        'status' => 'pending',
        'expires_at' => now()->subHour(),
    ]);

    $this->artisan('booking:reminders');

    Mail::assertNothingQueued();
});

it('does not resend a warning if one already exists for that stage', function () {
    Mail::fake();

    $booking = Booking::factory()->create([
        'status' => 'pending',
        'expires_at' => now()->addMinutes(45), // inside 1h, 3h, and 12h windows
    ]);


    BookingReminder::create(['booking_id' => $booking->id, 'type' => '12h', 'sent_at' => now()->subHours(11)]);
    BookingReminder::create(['booking_id' => $booking->id, 'type' => '3h', 'sent_at' => now()->subHours(2)]);
    BookingReminder::create(['booking_id' => $booking->id, 'type' => '1h', 'sent_at' => now()->subMinutes(10)]);

    $this->artisan('booking:reminders');

    Mail::assertNothingQueued();
});

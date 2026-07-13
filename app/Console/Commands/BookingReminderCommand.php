<?php

namespace App\Console\Commands;

use App\Mail\BookingExpirationWarning;
use App\Models\Booking;
use App\Models\BookingReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingReminderCommand extends Command
{
    protected $signature = 'booking:reminders';

    protected $description = 'Send expiration warning emails for pending bookings at 12h, 3h and 1h before expiry';

    protected array $warningStages = [
        12 => '12h',
        3  => '3h',
        1  => '1h',
    ];

    public function handle(): int
    {
        $now = now();

        foreach ($this->warningStages as $hours => $type) {
            $this->sendWarningsFor($hours, $type, $now);
        }

        return self::SUCCESS;
    }

    protected function sendWarningsFor(int $hours, string $type, Carbon $now): void
    {
        $threshold = $now->copy()->addHours($hours);

        Booking::query()
            ->where('status', 'pending')
            ->where('expires_at', '>', $now)
            ->where('expires_at', '<=', $threshold)
            ->whereDoesntHave('reminders', fn ($q) => $q->where('type', $type))
            ->chunkById(100, function ($bookings) use ($hours, $type) {
                foreach ($bookings as $booking) {
                    $this->sendWarning($booking, $hours, $type);
                }
            });
    }

    protected function sendWarning(Booking $booking, int $hours, string $type): void
    {
        try {
            Mail::to($booking->user->email)
                ->queue(new BookingExpirationWarning(
                    $booking,
                    $hours === 1 ? '1 hour' : "{$hours} hours"
                ));

            // firstOrCreate guards against a race if two runs overlap
            BookingReminder::firstOrCreate(
                ['booking_id' => $booking->id, 'type' => $type],
                ['sent_at' => now()]
            );
        } catch (\Throwable $e) {
            Log::error("Failed to send {$hours}h booking expiration warning", [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

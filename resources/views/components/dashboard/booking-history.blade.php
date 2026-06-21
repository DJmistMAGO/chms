@props(['bookings'])

<div class="flex h-full flex-col rounded-3xl border border-gray-200 dark:border-white/10 bg-white/[0.03] p-6 shadow-lg transition hover:border-gray-300 hover:shadow-xl">
    <div class="flex items-start justify-between">
        <div>
            <h3 class="font-semibold text-gray-500 dark:text-white">Booking History</h3>
            <p class="mt-1 text-sm text-gray-500">Recently completed reservations and activity.</p>
        </div>
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-500/10">
            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    </div>

    <div class="mt-5 flex flex-col gap-3">
        @forelse ($bookings as $booking)
            <div class="flex flex-col gap-3 rounded-2xl border border-white/[0.06] bg-white/[0.04] p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">{{ $booking->room_type ?? 'Completed Appointment' }}</p>
                    <h4 class="mt-1 font-semibold text-gray-600 dark:text-white">{{ $booking->reference_number ?? ($booking->room_type ?? 'Reservation') }}</h4>
                    <p class="mt-1 text-sm text-gray-400">{{ optional($booking->check_in)->format('M j, Y') ?? 'Date not set' }} &bull; {{ optional($booking->check_out)->format('M j, Y') ?? '' }}</p>
                </div>
                <span class="w-fit rounded-full bg-white/[0.06] px-3 py-1 text-xs font-semibold text-gray-400">{{ $booking->status ?? 'Completed' }}</span>
            </div>
        @empty
            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.04] p-4 text-gray-500">
                No booking history found.
            </div>
        @endforelse
    </div>
</div>

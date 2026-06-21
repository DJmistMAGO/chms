@props(['bookings'])

<div class="flex-1 rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-sm transition hover:border-white/20">
    <div class="mb-4 flex items-start justify-between">
        <div>
            <h3 class="font-semibold text-gray-600 dark:text-white">Recent Bookings</h3>
            <p class="mt-1 text-sm text-gray-500">Latest confirmed reservations.</p>
        </div>
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-500/10">
            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
            </svg>
        </div>
    </div>

    <div class="flex flex-col gap-3">
        @forelse ($bookings->take(3) as $booking)
            <div class="flex items-center justify-between rounded-2xl border border-white/[0.06] bg-white/[0.04] px-4 py-3">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-white">{{ $booking->room_type ?? 'Reservation' }} — {{ $booking->user->name ?? 'Guest' }}</p>
                    <p class="mt-0.5 text-xs text-gray-500">{{ optional($booking->check_in)->format('M j, Y') ?? 'TBD' }} &bull; {{ optional($booking->check_in)->format('g:i A') ?? '' }} &rarr; {{ optional($booking->check_out)->format('M j, Y') ?? 'TBD' }} &bull; {{ optional($booking->check_out)->format('g:i A') ?? '' }} &bull; {{ $booking->check_in->diffInDays($booking->check_out) ?? 0 }} nights</p>
                </div>
                <span class="rounded-full bg-green-500/10 px-2.5 py-1 text-xs font-semibold text-green-400">{{ $booking->status ?? 'Confirmed' }}</span>
            </div>
        @empty
            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.04] p-4 text-gray-500">No recent bookings available.</div>
        @endforelse
    </div>
</div>

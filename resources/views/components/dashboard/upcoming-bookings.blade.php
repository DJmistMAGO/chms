@props(['bookings'])

@php
    $statusClasses = [
        'Pending' => 'bg-yellow-500/10 text-yellow-500',
        'Confirmed' => 'bg-blue-500/10 text-blue-500',
        'Verified' => 'bg-green-500/10 text-green-500',
        'Canceled' => 'bg-red-500/10 text-red-500',
        'Expired' => 'bg-gray-500/10 text-gray-500',
    ];
@endphp

<div class="flex h-full flex-col rounded-3xl border border-gray-200 dark:border-white/10 bg-white/[0.03] p-6 shadow-lg transition hover:border-gray-300 hover:shadow-xl">
    <div class="flex items-start justify-between">
        <div>
            <h3 class="font-semibold text-gray-500 dark:text-white">Upcoming Bookings</h3>
            <p class="mt-1 text-sm text-gray-500">Your upcoming reservations and schedules.</p>
        </div>
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-500/10">
            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
            </svg>
        </div>
    </div>

    <div class="mt-5 flex flex-col gap-3">
        @forelse ($bookings as $booking)
            @php
                $status = $booking->status ?? 'Pending';
                $statusClass = $statusClasses[$status] ?? 'bg-gray-500/10 text-gray-500';
                $label = $status === 'Pending' ? 'Tomorrow' : ($status === 'Confirmed' ? 'In 2 days' : 'Soon');
            @endphp
            <div class="flex flex-col gap-3 rounded-2xl border border-blue-500/20 bg-blue-500/10 p-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-400">{{ $booking->room_type ?? 'Room Booking' }}</p>
                    <h4 class="mt-1 font-semibold text-gray-600 dark:text-white">{{ $booking->reference_number ?? 'Reservation' }}</h4>
                    <p class="mt-1 text-sm text-gray-400">{{ optional($booking->check_in)->format('M j, Y') ?? 'Date not set' }} &bull; {{ optional($booking->check_in)->format('g:i A') ?? '' }}</p>
                </div>
                <span class="w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ $label }}</span>
            </div>
        @empty
            <div class="rounded-2xl border border-white/[0.06] bg-white/[0.04] p-4 text-gray-500">
                No upcoming bookings available yet.
            </div>
        @endforelse
    </div>
</div>

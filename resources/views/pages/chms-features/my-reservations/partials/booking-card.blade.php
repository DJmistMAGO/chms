{{--
    Partial: partials/booking-card.blade.php
    Variables: $booking, $statusLabel, $statusColor (yellow|green), $pulse
--}}

@php
    $colors = [
        'yellow' => [
            'border'   => 'border-yellow-200/60 dark:border-yellow-400/10',
            'bg'       => 'bg-yellow-50/50 dark:bg-yellow-400/5',
            'hover'    => 'hover:border-yellow-300 hover:shadow-yellow-100/60 dark:hover:border-yellow-400/20',
            'icon_bg'  => 'bg-yellow-400/20',
            'icon'     => 'text-yellow-600',
            'label'    => 'text-yellow-600/70 dark:text-yellow-400/60',
            'badge_bg' => 'bg-yellow-400/20',
            'badge_tx' => 'text-yellow-700 dark:text-yellow-400',
            'dot'      => 'bg-yellow-500',
            'ref_bdr'  => 'border-yellow-300/60 dark:border-yellow-400/10',
            'divider'  => 'border-yellow-200/50 dark:border-yellow-400/10',
        ],
        'green' => [
            'border'   => 'border-green-200/60 dark:border-green-400/10',
            'bg'       => 'bg-green-50/50 dark:bg-green-400/5',
            'hover'    => 'hover:border-green-300 hover:shadow-green-100/60 dark:hover:border-green-400/20',
            'icon_bg'  => 'bg-green-400/20',
            'icon'     => 'text-green-600',
            'label'    => 'text-green-600/70 dark:text-green-400/60',
            'badge_bg' => 'bg-green-400/20',
            'badge_tx' => 'text-green-700 dark:text-green-400',
            'dot'      => 'bg-green-500',
            'ref_bdr'  => 'border-green-300/60 dark:border-green-400/10',
            'divider'  => 'border-green-200/50 dark:border-green-400/10',
        ],
    ];
    $c = $colors[$statusColor] ?? $colors['yellow'];
@endphp

<div class="group relative flex flex-col rounded-2xl border {{ $c['border'] }} {{ $c['bg'] }} p-5
            transition-all hover:shadow-md {{ $c['hover'] }}">

    {{-- Top row: room type + status badge --}}
    <div class="mb-4 flex items-start justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl {{ $c['icon_bg'] }}">
                <svg class="h-4 w-4 {{ $c['icon'] }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21" />
                </svg>
            </span>
            <div>
                <p class="text-xs font-medium {{ $c['label'] }}">Room Type</p>
                <h4 class="text-sm font-semibold text-gray-800 dark:text-white">{{ $booking->room_type }}</h4>
            </div>
        </div>

        {{-- Status badge --}}
        <span class="inline-flex flex-shrink-0 items-center gap-1 rounded-full {{ $c['badge_bg'] }} px-2.5 py-1 text-xs font-semibold {{ $c['badge_tx'] }}">
            <span class="h-1.5 w-1.5 rounded-full {{ $c['dot'] }} {{ $pulse ? 'animate-pulse' : '' }}"></span>
            {{ $statusLabel }}
        </span>
    </div>

    {{-- Reference number --}}
    <div class="mb-3 rounded-xl border border-dashed {{ $c['ref_bdr'] }} bg-white/70 px-3 py-2 dark:bg-white/5">
        <p class="text-xs text-gray-400 dark:text-gray-500">Reference No.</p>
        <p class="mt-0.5 font-mono text-sm font-bold tracking-widest text-gray-800 dark:text-white">
            {{ $booking->reference_number }}
        </p>
    </div>

    {{-- Dates + nights --}}
    <div class="mb-3 flex items-center gap-2">
        <svg class="h-3.5 w-3.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
        </svg>
        <span class="text-xs text-gray-500 dark:text-gray-400">
            {{ $booking->check_in->format('M j, Y') }}
            <span class="mx-1 text-gray-300">→</span>
            {{ $booking->check_out->format('M j, Y') }}
        </span>
        <span class="ml-auto flex-shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-white/10 dark:text-gray-300">
            {{ $booking->check_in->diffInDays($booking->check_out) }}N
        </span>
    </div>

    {{-- Divider --}}
    <div class="my-3 border-t {{ $c['divider'] }}"></div>

    {{-- Total + booked at --}}
    <div class="flex items-end justify-between">
        <div>
            <p class="text-xs text-gray-400">Total</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white">
                ₱{{ number_format($booking->total_price, 2) }}
            </p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-400">Booked</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ $booking->created_at->format('M j, Y') }}
            </p>
        </div>
    </div>

</div>

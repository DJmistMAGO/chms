        @extends('layouts.authenticated.app')
        @section('title', 'Booking History')

        @section('content')
            <div x-data="{
                assignModal: false,
                cancelModal: false,
                deleteModal: false,
                detailModal: false,
                selectedId: null,
                selectedRef: null,
                selectedRoomType: null,
                selectedBooking: {},
                open(modal, booking) {
                    this.selectedId = booking.id;
                    this.selectedRef = booking.ref;
                    this.selectedRoomType = booking.roomType;
                    this[modal] = true;
                }
            }">

                <x-common.page-breadcrumb pageTitle="Booking History" />

                <div
                    class="rounded-3xl border border-gray-200 bg-white px-6 py-8 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Header --}}
                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Booking History</h2>
                            <p class="mt-0.5 text-sm text-gray-400">Review and action booking history.</p>
                        </div>
                        <div
                            class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-center dark:border-blue-400/20 dark:bg-blue-400/10">
                            <p class="text-xs font-medium text-blue-600 dark:text-blue-400">Total</p>
                            <p class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ $bookingHistory->count() }}</p>
                        </div>
                    </div>

                    {{-- Search and Status Filter --}}
                    <div class="mb-5 flex flex-col gap-3 md:flex-row">

                        {{-- Search --}}
                        <div class="relative flex-1">
                            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z" />
                            </svg>

                            <input type="text" id="search-input" placeholder="Search by name, reference, room type..."
                                oninput="filterTable()"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-700 transition focus:border-orange-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white">
                        </div>

                        {{-- Status Filter --}}
                        <div class="md:w-56">
                            <select id="status-filter" onchange="filterTable()"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-700 transition focus:border-orange-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white">

                                <option value="">All Statuses</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                                @role('staff')
                                    <option value="archived">Archived</option>
                                @endrole
                            </select>
                        </div>

                    </div>

                    {{-- Table --}}
                    {{-- Table --}}
                    <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-800">
                        <table class="w-full min-w-[920px] text-sm" id="bookings-table">
                            <thead>
                                <tr
                                    class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:border-gray-800 dark:bg-white/5 dark:text-gray-500">
                                    @foreach (['Type', 'Guest', 'Reference', 'Room Type', 'Stay', 'Total', 'Status', 'Actions'] as $col)
                                        <th class="px-5 py-3.5 {{ $col === 'Actions' ? 'text-center' : '' }}">
                                            {{ $col }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-800" id="bookings-tbody">
                                @forelse ($bookingHistory as $b)
                                    @php
                                        $nights = $b->check_in->diffInDays($b->check_out);
                                        $isOnline = $b->booking_type === 'Online';

                                        // Name: online guests use user relationship, walk-in use fullname column
                                        $guestName = $isOnline
                                            ? $b->user->name ?? 'Registered Guest'
                                            : $b->fullname ?? '—';
                                        $guestEmail = $isOnline ? $b->user->email ?? '—' : $b->phone_number ?? '—';
                                        $guestSub = $isOnline ? 'email' : 'phone';

                                        // Initials from whichever name we have
                                        $nameParts = explode(' ', trim($guestName));
                                        $initials =
                                            strtoupper(substr($nameParts[0] ?? 'G', 0, 1)) .
                                            strtoupper(substr($nameParts[1] ?? 'G', 0, 1));
                                    @endphp

                                    <tr class="booking-row group transition-colors hover:bg-orange-50/40 dark:hover:bg-orange-400/5"
                                        data-search="{{ strtolower($guestName) }} {{ strtolower($b->reference_number) }} {{ strtolower($b->room_type) }} {{ strtolower($b->booking_type) }}">

                                        {{-- Booking Type --}}
                                        <td class="px-5 py-4">
                                            @if ($isOnline)
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-200 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/20">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253" />
                                                    </svg>
                                                    Online
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-yellow-50 px-2.5 py-1 text-xs font-semibold text-yellow-700 ring-1 ring-yellow-200 dark:bg-yellow-400/10 dark:text-yellow-400 dark:ring-yellow-400/20">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                    </svg>
                                                    Walk-in
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Guest --}}
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl text-xs font-bold
                                {{ $isOnline
                                    ? 'bg-blue-100 text-blue-700 dark:bg-blue-400/20 dark:text-blue-300'
                                    : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-400/20 dark:text-yellow-300' }}">
                                                    {{ $initials }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-800 dark:text-white">
                                                        {{ $guestName }}</p>
                                                    <p class="text-xs text-gray-400">
                                                        @if ($isOnline)
                                                            {{ $guestEmail }}
                                                        @else
                                                            <span class="text-gray-300 dark:text-gray-600">📞</span>
                                                            {{ $guestEmail }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Reference --}}
                                        <td class="px-5 py-4">
                                            <span
                                                class="font-mono text-xs font-semibold tracking-widest text-gray-700 dark:text-gray-300">
                                                {{ $b->reference_number }}
                                            </span>
                                        </td>

                                        {{-- Room Type --}}
                                        <td class="px-5 py-4">
                                            <span
                                                class="rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-300">
                                                {{ $b->room->room_type }}
                                            </span>
                                        </td>

                                        {{-- Stay --}}
                                        <td class="px-5 py-4">
                                            <p class="font-medium text-gray-700 dark:text-gray-300">
                                                {{ $b->check_in->format('M j') }} – {{ $b->check_out->format('M j, Y') }}
                                            </p>
                                            <p class="mt-0.5 text-xs text-gray-400">
                                                {{ $nights }} night{{ $nights == 1 ? '' : 's' }}
                                            </p>
                                        </td>

                                        {{-- Total --}}
                                        <td class="px-5 py-4">
                                            <span class="font-semibold text-gray-800 dark:text-white text-xs">
                                                ₱{{ number_format($b->total_price, 2) }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-5 py-4 status-cell">
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-200 dark:bg-blue-400/10 dark:text-blue-400 dark:ring-blue-400/20">
                                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-blue-500"></span>
                                                {{ $b->status }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-5 py-4">
                                            <div class="flex items-center justify-center gap-1.5">

                                                {{-- View --}}
                                                <button title="View booking details"
                                                    @click="selectedBooking = {
                                    reference_number:      '{{ $b->reference_number }}',
                                    booking_type:          '{{ $b->booking_type }}',
                                    guest_name:            '{{ addslashes($guestName) }}',
                                    guest_contact:         '{{ $isOnline ? $b->user->email ?? '—' : $b->phone_number ?? '—' }}',
                                    room_type:             '{{ $b->room->room_type }}',
                                    check_in:              '{{ $b->check_in->format('M j, Y') }}',
                                    check_out:             '{{ $b->check_out->format('M j, Y') }}',
                                    number_of_guests:      '{{ $b->number_of_guests }}',
                                    floor_level:           '{{ $b->room->floor_level }}',
                                    ambiance:              '{{ $b->ambiance }}',
                                    food_package:          '{{ $b->food_package }}',
                                    room_price:            '{{ number_format($b->room_price, 2) }}',
                                    micro_pricing_amount:  '{{ number_format($b->micro_pricing_amount ?? 0, 2) }}',
                                    total_price:           '{{ number_format($b->total_price, 2) }}',
                                    status:                '{{ $b->status }}',
                                    nights:                '{{ $nights }}',
                                    booked_at:             '{{ $b->created_at->format('M j, Y, g:i A') }}'
                                }; detailModal=true"
                                                    class="flex h-8 w-8 items-center justify-center rounded-xl bg-gray-50 text-gray-500 transition hover:bg-gray-100 hover:scale-105 dark:bg-white/5 dark:text-gray-400 dark:hover:bg-white/10">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        stroke-width="2.2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-16 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <span
                                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-50 dark:bg-orange-400/10">
                                                    <svg class="h-5 w-5 text-orange-400" fill="none"
                                                        stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                                    </svg>
                                                </span>
                                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No
                                                    checked-in
                                                    bookings</p>
                                                <p class="text-xs text-gray-400">All caught up! No guests are currently
                                                    checked in.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($bookingHistory->hasPages())
                        <div class="mt-5">{{ $bookingHistory->links() }}</div>
                    @endif

                </div>

                {{-- DETAIL MODAL --}}
                <div x-show="detailModal" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div @click.away="detailModal=false"
                        class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                        {{-- Header --}}
                        <div class="relative flex items-center justify-between px-6 py-4" style="background:#FFD000;">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-widest"
                                    style="color:rgba(28,28,30,0.45);">
                                    Reference</p>
                                <h3 class="font-mono text-base font-bold text-gray-900"
                                    x-text="selectedBooking.reference_number || '—'"></h3>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-black/10 px-2.5 py-1 text-xs font-semibold text-gray-800">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gray-800"></span>
                                    <span x-text="selectedBooking.status || '—'"></span>
                                </span>
                                <button @click="detailModal=false"
                                    class="flex h-7 w-7 items-center justify-center rounded-full bg-black/10 text-gray-800 hover:bg-black/20 transition">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Body — two-column layout, no scroll --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-6 py-5">

                            {{-- LEFT COLUMN --}}
                            <div class="space-y-3">

                                {{-- Room + guests row --}}
                                <div
                                    class="flex items-center gap-3 rounded-xl bg-yellow-50 px-3 py-2.5 dark:bg-yellow-400/10">
                                    <span
                                        class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-yellow-400/30">
                                        <svg class="h-3.5 w-3.5 text-yellow-700" fill="none" stroke="currentColor"
                                            stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21" />
                                        </svg>
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="truncate text-sm font-semibold text-yellow-900 dark:text-yellow-300"
                                            x-text="selectedBooking.room_type || '—'"></p>
                                        <p class="text-xs text-yellow-700/60"
                                            x-text="(selectedBooking.number_of_guests || '—') + ' guest(s)'"></p>
                                    </div>
                                    <span
                                        class="flex-shrink-0 rounded-full bg-yellow-200/60 px-2 py-0.5 text-xs font-semibold text-yellow-800"
                                        x-text="(selectedBooking.nights || '—') + 'N'"></span>
                                </div>

                                {{-- Dates --}}
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach (['check_in' => 'Check-in', 'check_out' => 'Check-out'] as $key => $label)
                                        <div
                                            class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/5">
                                            <p class="text-xs text-gray-400">{{ $label }}</p>
                                            <p class="mt-0.5 text-sm font-semibold text-gray-800 dark:text-white"
                                                x-text="selectedBooking.{{ $key }} || '—'"></p>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Pricing breakdown --}}
                                <div
                                    class="rounded-xl border border-yellow-100 bg-yellow-50/50 px-3 py-3 dark:border-yellow-400/10 dark:bg-yellow-400/5">
                                    <div class="space-y-1.5 text-sm">
                                        <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                            <span>Room rate</span><span
                                                x-text="selectedBooking.room_price ? '₱' + selectedBooking.room_price : '—'"></span>
                                        </div>
                                        <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                            <span>Add-ons</span><span
                                                x-text="selectedBooking.micro_pricing_amount ? '₱' + selectedBooking.micro_pricing_amount : '₱0.00'"></span>
                                        </div>
                                        <div
                                            class="flex justify-between border-t border-yellow-200/60 pt-1.5 dark:border-yellow-400/10">
                                            <span class="font-semibold text-gray-700 dark:text-gray-200">Total</span>
                                            <span class="font-bold text-gray-900 dark:text-white"
                                                x-text="selectedBooking.total_price ? '₱' + selectedBooking.total_price : '—'"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- RIGHT COLUMN — Stay Preferences --}}
                            <div class="space-y-2">
                                <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-gray-400">Stay
                                    Preferences</p>

                                {{-- Floor Level --}}
                                <div
                                    class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/5">
                                    <span
                                        class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-400/15">
                                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                        </svg>
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-400">Floor Level</p>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white"
                                            x-text="selectedBooking.floor_level || 'No preference'"></p>
                                    </div>
                                </div>

                                {{-- Ambiance --}}
                                <div
                                    class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/5">
                                    <span
                                        class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-400/15">
                                        <svg class="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M18 17v2M12 5.5V10m-6 7v2m15-2v-4c0-1.6569-1.3431-3-3-3H6c-1.65685 0-3 1.3431-3 3v4h18Zm-2-7V8c0-1.65685-1.3431-3-3-3H8C6.34315 5 5 6.34315 5 8v2h14Z" />
                                        </svg>

                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-400">Ambiance</p>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white"
                                            x-text="selectedBooking.ambiance || 'Regular Room'"></p>
                                    </div>
                                </div>

                                {{-- Food Package --}}
                                <div
                                    class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/5">
                                    <span
                                        class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-400/15">
                                        <svg class="h-4 w-4 text-orange-600 dark:text-orange-400" fill="none"
                                            stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21M6.375 6.375 5.25 5.25m13.5 1.125-1.125-1.125m1.125 13.5-1.125-1.125M6.375 17.625 5.25 18.75M3 12a9 9 0 1 1 18 0 9 9 0 0 1-18 0Z" />
                                        </svg>
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-gray-400">Food Package</p>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white"
                                            x-text="selectedBooking.food_package || 'No Food'"></p>
                                    </div>

                                </div>

                                {{-- Special requests (only shows if present) --}}
                                <div x-show="selectedBooking.special_requests && selectedBooking.special_requests !== '—'"
                                    class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/5">
                                    <p class="text-xs text-gray-400">Special Requests</p>
                                    <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-400"
                                        x-text="selectedBooking.special_requests"></p>
                                </div>

                            </div>

                        </div>

                        {{-- Footer --}}
                        <div
                            class="flex items-center justify-between border-t border-gray-100 px-6 py-3 dark:border-gray-800">
                            <p class="text-xs text-gray-400">Booked <span class="text-gray-500 dark:text-gray-300"
                                    x-text="selectedBooking.booked_at || '—'"></span></p>
                            <button @click="detailModal=false"
                                class="rounded-xl border border-gray-200 px-4 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Close</button>
                        </div>
                    </div>
                </div>

            </div>

            <script>
                function filterTable() {
                    // 1. Get filter inputs in lowercase
                    const search = document.getElementById('search-input').value.toLowerCase();
                    const status = document.getElementById('status-filter').value.toLowerCase();

                    const rows = document.querySelectorAll('.booking-row');

                    rows.forEach(row => {
                        const rowText = row.textContent.toLowerCase();

                        // 2. FIXED: Changed .toUpperCase() to .toLowerCase() to match your filter variable
                        const rowStatus = row.querySelector('.status-cell')?.textContent.trim().toLowerCase() || '';

                        const matchesSearch = rowText.includes(search);
                        const matchesStatus = !status || rowStatus === status;

                        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
                    });
                }
            </script>
        @endsection

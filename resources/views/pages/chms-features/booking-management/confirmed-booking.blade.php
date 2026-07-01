@extends('layouts.authenticated.app')
@section('title', 'Confirmed Bookings')

@section('content')
<div x-data="{
    assignModal: false, cancelModal: false, deleteModal: false, detailModal: false,
    selectedId: null, selectedRef: null, selectedRoomType: null, selectedBooking: {},
    open(modal, booking) {
        this.selectedId = booking.id;
        this.selectedRef = booking.ref;
        this.selectedRoomType = booking.roomType;
        this[modal] = true;
    }
}">

    <x-common.page-breadcrumb pageTitle="Confirmed Bookings" />

    <div class="rounded-3xl border border-gray-200 bg-white px-6 py-8 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Confirmed Bookings</h2>
                <p class="mt-0.5 text-sm text-gray-400">Review and action confirmed reservation requests.</p>
            </div>
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-2.5 text-center dark:border-green-400/20 dark:bg-green-400/10">
                <p class="text-xs font-medium text-green-600 dark:text-green-400">Confirmed</p>
                <p class="text-xl font-bold text-green-700 dark:text-green-300">{{ $confirmedBookings->count() }}</p>
            </div>
        </div>

        {{-- Search --}}
        <div class="mb-5 relative">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z"/>
            </svg>
            <input type="text" id="search-input" placeholder="Search by name, reference, room type…"
                oninput="filterTable()"
                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-700 transition focus:border-green-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white">
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-800">
            <table class="w-full min-w-[820px] text-sm" id="bookings-table">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:border-gray-800 dark:bg-white/5 dark:text-gray-500">
                        @foreach(['Guest','Reference','Room Type','Stay','Total','Status','Actions'] as $col)
                            <th class="px-5 py-3.5 {{ $col === 'Actions' ? 'text-center' : '' }}">{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800" id="bookings-tbody">
                    @forelse ($confirmedBookings as $b)
                        @php
                            $nights = $b->check_in->diffInDays($b->check_out);
                            $initials = strtoupper(substr($b->user->name ?? 'G', 0, 1))
                                      . strtoupper(substr(strstr($b->user->name ?? ' G', ' '), 1, 1));
                        @endphp
                        <tr class="booking-row group transition-colors hover:bg-green-50/40 dark:hover:bg-green-400/5"
                            data-search="{{ strtolower($b->user->name ?? '') }} {{ strtolower($b->reference_number) }} {{ strtolower($b->room_type) }}">

                            {{-- Guest --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-green-100 text-xs font-bold text-green-700 dark:bg-green-400/20 dark:text-green-300">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-white">{{ $b->user->name ?? '—' }}</p>
                                        <p class="text-xs text-gray-400">{{ $b->user->email ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Reference --}}
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs font-semibold tracking-widest text-gray-700 dark:text-gray-300">{{ $b->reference_number }}</span>
                            </td>

                            {{-- Room Type --}}
                            <td class="px-5 py-4">
                                <span class="rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-300">{{ $b->room_type }}</span>
                            </td>

                            {{-- Stay --}}
                            <td class="px-5 py-4">
                                <p class="font-medium text-gray-700 dark:text-gray-300">{{ $b->check_in->format('M j') }} – {{ $b->check_out->format('M j, Y') }}</p>
                                <p class="mt-0.5 text-xs text-gray-400">{{ $nights }} night{{ $nights == 1 ? '' : 's' }}</p>
                            </td>

                            {{-- Total --}}
                            <td class="px-5 py-4">
                                <span class="font-semibold text-gray-800 dark:text-white">₱{{ number_format($b->total_price, 2) }}</span>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-green-200 dark:bg-green-400/10 dark:text-green-400 dark:ring-green-400/20">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-500"></span>Confirmed
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1.5">

                                    {{-- View --}}
                                    <button title="View booking details"
                                        @click="selectedBooking = {
                                            reference_number: '{{ $b->reference_number }}',
                                            room_type: '{{ $b->room_type }}',
                                            check_in: '{{ $b->check_in->format('M j, Y') }}',
                                            check_out: '{{ $b->check_out->format('M j, Y') }}',
                                            number_of_guests: '{{ $b->number_of_guests }}',
                                            room_price: '{{ number_format($b->room_price, 2) }}',
                                            micro_pricing_amount: '{{ number_format($b->micro_pricing_amount ?? 0, 2) }}',
                                            total_price: '{{ number_format($b->total_price, 2) }}',
                                            status: '{{ ucfirst($b->status ?? 'pending') }}',
                                            nights: '{{ $nights }}',
                                            booked_at: '{{ $b->created_at->format('M j, Y, g:i A') }}'
                                        }; detailModal=true"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition hover:bg-blue-100 hover:scale-105 dark:bg-blue-400/10 dark:text-blue-400 dark:hover:bg-blue-400/20">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5Z"/>
                                        </svg>
                                    </button>

                                    {{-- Confirm --}}
                                    <button title="Check-in booking"
                                        @click="selectedId='{{ $b->id }}'; selectedRef='{{ $b->reference_number }}'; selectedRoomType='{{ $b->room_type }}'; assignModal=true"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-green-50 text-green-600 transition hover:bg-green-100 hover:scale-105 dark:bg-green-400/10 dark:text-green-400 dark:hover:bg-green-400/20">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>

                                    {{-- Cancel --}}
                                    <button title="Cancel booking"
                                        @click="selectedId='{{ $b->id }}'; selectedRef='{{ $b->reference_number }}'; cancelModal=true"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition hover:bg-amber-100 hover:scale-105 dark:bg-amber-400/10 dark:text-amber-400 dark:hover:bg-amber-400/20">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>

                                    {{-- Delete --}}
                                    {{-- <button title="Delete booking"
                                        @click="selectedId='{{ $b->id }}'; selectedRef='{{ $b->reference_number }}'; deleteModal=true"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-red-50 text-red-500 transition hover:bg-red-100 hover:scale-105 dark:bg-red-400/10 dark:text-red-400 dark:hover:bg-red-400/20">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7L18 20H6L5 7M10 11v6M14 11v6M4 7h16M9 7V4h6v3"/>
                                        </svg>
                                    </button> --}}

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-green-50 dark:bg-green-400/10">
                                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                        </svg>
                                    </span>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No confirmed bookings</p>
                                    <p class="text-xs text-gray-400">All caught up! No reservations need attention.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($confirmedBookings->hasPages())
            <div class="mt-5">{{ $confirmedBookings->links() }}</div>
        @endif

    </div>

    {{-- DETAIL MODAL --}}
    <div x-show="detailModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.away="detailModal=false" class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="relative flex items-center justify-between px-5 py-4" style="background:#FFD000;">
                <div>
                    <p class="text-xs font-medium uppercase tracking-widest" style="color:rgba(28,28,30,0.45);">Reference</p>
                    <h3 class="font-mono text-base font-bold text-gray-900" x-text="selectedBooking.reference_number || '—'"></h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 rounded-full bg-black/10 px-2.5 py-1 text-xs font-semibold text-gray-800">
                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-gray-800"></span>
                        <span x-text="selectedBooking.status || '—'"></span>
                    </span>
                    <button @click="detailModal=false" class="flex h-7 w-7 items-center justify-center rounded-full bg-black/10 text-gray-800 hover:bg-black/20 transition">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="px-5 py-4 space-y-4">
                <div class="flex items-center gap-3 rounded-xl bg-green-50 px-3 py-2.5 dark:bg-green-400/10">
                    <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-green-400/30">
                        <svg class="h-3.5 w-3.5 text-green-700" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21"/>
                        </svg>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-semibold text-green-900 dark:text-green-300" x-text="selectedBooking.room_type || '—'"></p>
                        <p class="text-xs text-green-700/60" x-text="(selectedBooking.number_of_guests || '—') + ' guest(s)'"></p>
                    </div>
                    <span class="flex-shrink-0 rounded-full bg-green-200/60 px-2 py-0.5 text-xs font-semibold text-green-800" x-text="(selectedBooking.nights || '—') + 'N'"></span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['check_in' => 'Check-in', 'check_out' => 'Check-out'] as $key => $label)
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/5">
                        <p class="text-xs text-gray-400">{{ $label }}</p>
                        <p class="mt-0.5 text-sm font-semibold text-gray-800 dark:text-white" x-text="selectedBooking.{{ $key }} || '—'"></p>
                    </div>
                    @endforeach
                </div>
                <div class="rounded-xl border border-green-100 bg-green-50/50 px-3 py-3 dark:border-green-400/10 dark:bg-green-400/5">
                    <div class="space-y-1.5 text-sm">
                        <div class="flex justify-between text-gray-500 dark:text-gray-400">
                            <span>Room rate</span><span x-text="selectedBooking.room_price ? '₱' + selectedBooking.room_price : '—'"></span>
                        </div>
                        <div class="flex justify-between text-gray-500 dark:text-gray-400">
                            <span>Add-ons</span><span x-text="selectedBooking.micro_pricing_amount ? '₱' + selectedBooking.micro_pricing_amount : '₱0.00'"></span>
                        </div>
                        <div class="flex justify-between border-t border-green-200/60 pt-1.5 dark:border-green-400/10">
                            <span class="font-semibold text-gray-700 dark:text-gray-200">Total</span>
                            <span class="font-bold text-gray-900 dark:text-white" x-text="selectedBooking.total_price ? '₱' + selectedBooking.total_price : '—'"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 px-5 py-3 dark:border-gray-800">
                <p class="text-xs text-gray-400">Booked <span class="text-gray-500 dark:text-gray-300" x-text="selectedBooking.booked_at || '—'"></span></p>
                <button @click="detailModal=false" class="rounded-xl border border-gray-200 px-4 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">Close</button>
            </div>
        </div>
    </div>

    {{-- CONFIRM MODAL --}}
    <div x-show="assignModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.away="assignModal=false" class="w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl "
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Header (Restored to flat bg-green-500) -->
            <div class="bg-green-500 px-6 py-5 flex items-center justify-between shadow-sm">
                <div>
                    <h3 class="text-lg font-bold text-white tracking-tight">Confirm Guest Arrival</h3>
                    <p class="mt-0.5 text-xs text-green-100 font-mono" x-text="'Ref: ' + selectedRef"></p>
                </div>
                <button type="button" @click="assignModal=false" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-white transition-colors hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Form & Content -->
            <form method="POST" x-bind:action="'{{ url('booking/activate') }}/' + selectedRef">
                @csrf @method('PUT')
                <input type="hidden" name="reference_number" x-model="selectedRef">

                <div class="px-6 py-6 space-y-4">
                    <!-- Informative Summary Box -->
                    <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-800">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400 rounded-xl mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Ready to activate stay?</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                    Clicking confirm will instantly update booking <span class="font-mono font-bold text-gray-800 dark:text-gray-200" x-text="selectedRef"></span> to <span class="font-semibold text-green-600 dark:text-green-400">Checked In</span> status.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions (Restored primary button to flat bg-green-500) -->
                <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <button type="button" @click="assignModal=false" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 transition-all hover:bg-gray-50 hover:text-gray-800 active:scale-98 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-xl bg-green-500 px-5 py-2 text-sm font-semibold text-white transition-all hover:bg-green-600 active:scale-95 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        Confirm Guest Arrival
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- CANCEL MODAL --}}
    <div x-show="cancelModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.away="cancelModal=false" class="w-full max-w-sm rounded-3xl bg-white shadow-2xl dark:bg-gray-900"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="rounded-t-3xl bg-amber-500 px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Cancel Booking?</h3>
                    <p class="mt-0.5 text-xs text-amber-100" x-text="'Ref: ' + selectedRef"></p>
                </div>
                <button @click="cancelModal=false" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" x-bind:action="">
                @csrf @method('PATCH')
                <div class="px-6 py-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">This will mark the booking as <span class="font-semibold text-amber-600">cancelled</span>. The guest will be notified.</p>
                    <div class="mt-4">
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-widest text-gray-400">Reason (optional)</label>
                        <textarea name="cancel_reason" rows="2" placeholder="Reason for cancellation…"
                            class="w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                    <button type="button" @click="cancelModal=false" class="rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400">Go Back</button>
                    <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2 text-sm font-semibold text-white hover:bg-amber-600 active:scale-95">Yes, Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.away="deleteModal=false" class="w-full max-w-sm rounded-3xl bg-white shadow-2xl dark:bg-gray-900"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="rounded-t-3xl bg-red-500 px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Delete Booking?</h3>
                    <p class="mt-0.5 text-xs text-red-100" x-text="'Ref: ' + selectedRef"></p>
                </div>
                <button @click="deleteModal=false" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" x-bind:action="">
                @csrf @method('DELETE')
                <div class="px-6 py-5">
                    <div class="flex items-start gap-3 rounded-xl bg-red-50 p-4 dark:bg-red-400/10">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                        <p class="text-sm text-red-700 dark:text-red-300">This action <span class="font-bold">cannot be undone.</span> The booking record will be permanently deleted.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                    <button type="button" @click="deleteModal=false" class="rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400">Cancel</button>
                    <button type="submit" class="rounded-xl bg-red-500 px-5 py-2 text-sm font-semibold text-white hover:bg-red-600 active:scale-95">Delete Permanently</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function filterTable() {
        const q = document.getElementById('search-input').value.toLowerCase();
        document.querySelectorAll('.booking-row').forEach(r => {
            r.style.display = (r.dataset.search || '').includes(q) ? '' : 'none';
        });
    }
</script>
@endsection

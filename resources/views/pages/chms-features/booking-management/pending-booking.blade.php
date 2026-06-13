@extends('layouts.authenticated.app')

@section('title', 'Pending Bookings')

@section('content')

<div x-data="{
    assignModal: false,
    cancelModal:  false,
    deleteModal:  false,
    detailModal:  false,
    selectedId:   null,
    selectedRef:  null,
    selectedBooking: {},
}">

    <x-common.page-breadcrumb pageTitle="Pending Bookings" />

    <div class="rounded-3xl border border-gray-200 bg-white px-6 py-8 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- ── HEADER ── --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Pending Bookings</h2>
                <p class="mt-0.5 text-sm text-gray-400">Review and action incoming reservation requests.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="rounded-2xl border border-yellow-200 bg-yellow-50 px-4 py-2.5 text-center dark:border-yellow-400/20 dark:bg-yellow-400/10">
                    <p class="text-xs font-medium text-yellow-600 dark:text-yellow-400">Pending</p>
                    <p class="text-xl font-bold text-yellow-700 dark:text-yellow-300">{{ $pendingBookings->count() }}</p>
                </div>
            </div>
        </div>

        {{-- ── SEARCH ── --}}
        <div class="mb-5 flex items-center gap-3">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z" />
                </svg>
                <input type="text" id="search-input" placeholder="Search by name, reference, room type…"
                    oninput="filterTable()"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-700 transition focus:border-yellow-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white">
            </div>
        </div>

        {{-- ── TABLE ── --}}
        <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-gray-800">
            <table class="w-full min-w-[820px] text-sm" id="bookings-table">

                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/80 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:border-gray-800 dark:bg-white/5 dark:text-gray-500">
                        <th class="px-5 py-3.5">Guest</th>
                        <th class="px-5 py-3.5">Reference</th>
                        <th class="px-5 py-3.5">Room Type</th>
                        <th class="px-5 py-3.5">Stay</th>
                        <th class="px-5 py-3.5">Total</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50 dark:divide-gray-800" id="bookings-tbody">
                    @forelse ($pendingBookings as $booking)
                        <tr class="booking-row group transition-colors hover:bg-yellow-50/40 dark:hover:bg-yellow-400/5"
                            data-search="{{ strtolower($booking->user->name ?? '') }} {{ strtolower($booking->reference_number) }} {{ strtolower($booking->room_type) }}">

                            {{-- Guest --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-yellow-100 text-xs font-bold text-yellow-700 dark:bg-yellow-400/20 dark:text-yellow-300">
                                        {{ strtoupper(substr($booking->user->name ?? 'G', 0, 1)) }}{{ strtoupper(substr(strstr($booking->user->name ?? ' G', ' '), 1, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-white">{{ $booking->user->name ?? '—' }}</p>
                                        <p class="text-xs text-gray-400">{{ $booking->user->email ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Reference --}}
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs font-semibold tracking-widest text-gray-700 dark:text-gray-300">
                                    {{ $booking->reference_number }}
                                </span>
                            </td>

                            {{-- Room Type --}}
                            <td class="px-5 py-4">
                                <span class="rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-300">
                                    {{ $booking->room_type }}
                                </span>
                            </td>

                            {{-- Stay --}}
                            <td class="px-5 py-4">
                                <p class="font-medium text-gray-700 dark:text-gray-300">
                                    {{ $booking->check_in->format('M j') }} - {{ $booking->check_out->format('M j, Y') }}
                                </p>
                                <p class="mt-0.5 text-xs text-gray-400">
                                    {{ $booking->check_in->diffInDays($booking->check_out) }} night{{ $booking->check_in->diffInDays($booking->check_out) == 1 ? '' : 's' }}
                                </p>
                            </td>

                            {{-- Total --}}
                            <td class="px-5 py-4">
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    ₱{{ number_format($booking->total_price, 2) }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700 ring-1 ring-yellow-200 dark:bg-yellow-400/10 dark:text-yellow-400 dark:ring-yellow-400/20">
                                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-yellow-500"></span>
                                    Pending
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- View Details --}}
                                    <button
                                        data-reference-number="{{ $booking->reference_number }}"
                                        data-room-type="{{ $booking->room_type }}"
                                        data-check-in="{{ $booking->check_in->format('M j, Y') }}"
                                        data-check-out="{{ $booking->check_out->format('M j, Y') }}"
                                        data-number-of-guests="{{ $booking->number_of_guests }}"
                                        data-room-price="₱{{ number_format($booking->room_price, 2) }}"
                                        data-micro-pricing-amount="₱{{ number_format($booking->micro_pricing_amount ?? 0, 2) }}"
                                        data-total-price="₱{{ number_format($booking->total_price, 2) }}"
                                        data-status="{{ ucfirst($booking->status ?? 'pending') }}"
                                        @click="selectedBooking = {
                                            reference_number: $el.dataset.referenceNumber,
                                            room_type: $el.dataset.roomType,
                                            check_in: $el.dataset.checkIn,
                                            check_out: $el.dataset.checkOut,
                                            number_of_guests: $el.dataset.numberOfGuests,
                                            room_price: $el.dataset.roomPrice,
                                            micro_pricing_amount: $el.dataset.microPricingAmount,
                                            total_price: $el.dataset.totalPrice,
                                            status: $el.dataset.status,
                                        }; detailModal=true"
                                        title="View booking details"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition hover:bg-blue-100 hover:scale-105 dark:bg-blue-400/10 dark:text-blue-400 dark:hover:bg-blue-400/20">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5Z" />
                                        </svg>
                                    </button>

                                    {{-- Confirm --}}
                                    <button
                                        @click="selectedId='{{ $booking->id }}'; selectedRef='{{ $booking->reference_number }}'; assignModal=true"
                                        title="Confirm booking"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-green-50 text-green-600 transition hover:bg-green-100 hover:scale-105 dark:bg-green-400/10 dark:text-green-400 dark:hover:bg-green-400/20">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>

                                    {{-- Cancel --}}
                                    <button
                                        @click="selectedId='{{ $booking->id }}'; selectedRef='{{ $booking->reference_number }}'; cancelModal=true"
                                        title="Cancel booking"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition hover:bg-amber-100 hover:scale-105 dark:bg-amber-400/10 dark:text-amber-400 dark:hover:bg-amber-400/20">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>

                                    {{-- Delete --}}
                                    <button
                                        @click="selectedId='{{ $booking->id }}'; selectedRef='{{ $booking->reference_number }}'; deleteModal=true"
                                        title="Delete booking"
                                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-red-50 text-red-500 transition hover:bg-red-100 hover:scale-105 dark:bg-red-400/10 dark:text-red-400 dark:hover:bg-red-400/20">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7L18 20H6L5 7M10 11v6M14 11v6M4 7h16M9 7V4h6v3" />
                                        </svg>
                                    </button>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-50 dark:bg-yellow-400/10">
                                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        </svg>
                                    </span>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No pending bookings</p>
                                    <p class="text-xs text-gray-400">All caught up! No reservations need attention.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        @if ($pendingBookings->hasPages())
            <div class="mt-5">
                {{ $pendingBookings->links() }}
            </div>
        @endif

    </div>

    {{-- BOOKING DETAILS MODAL --}}
    <div x-show="detailModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.away="detailModal=false"
            class="w-full max-w-lg rounded-3xl bg-white shadow-2xl dark:bg-gray-900"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="rounded-t-3xl bg-blue-500 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Booking Details</h3>
                        <p class="mt-0.5 text-xs text-blue-100" x-text="'Ref: ' + (selectedBooking.reference_number || '')"></p>
                    </div>
                    <button @click="detailModal=false"
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="grid gap-3 px-6 py-5 sm:grid-cols-2">
                <template x-for="field in [
                    { label: 'Reference Number', value: selectedBooking.reference_number },
                    { label: 'Room Type', value: selectedBooking.room_type },
                    { label: 'Check-in', value: selectedBooking.check_in },
                    { label: 'Check-out', value: selectedBooking.check_out },
                    { label: 'Guests', value: selectedBooking.number_of_guests },
                    { label: 'Room Price', value: selectedBooking.room_price },
                    { label: 'Micro Pricing', value: selectedBooking.micro_pricing_amount },
                    { label: 'Total Price', value: selectedBooking.total_price },
                    { label: 'Status', value: selectedBooking.status },
                ]" :key="field.label">
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-white/5">
                        <p class="text-xs font-medium uppercase tracking-widest text-gray-400" x-text="field.label"></p>
                        <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white" x-text="field.value || '—'"></p>
                    </div>
                </template>
            </div>

            <div class="flex justify-end border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                <button type="button" @click="detailModal=false"
                    class="rounded-xl bg-blue-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-blue-600 active:scale-95">
                    Close
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         CONFIRM / ASSIGN MODAL
    ══════════════════════════════════════════ --}}
    <div x-show="assignModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.away="assignModal=false"
            class="w-full max-w-md rounded-3xl bg-white shadow-2xl dark:bg-gray-900"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Header --}}
            <div class="rounded-t-3xl bg-green-500 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Confirm Booking</h3>
                        <p class="mt-0.5 text-xs text-green-100" x-text="'Ref: ' + selectedRef"></p>
                    </div>
                    <button @click="assignModal=false"
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <form method="POST" action=""
                x-bind:action="">
                @csrf
                @method('PATCH')

                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-widest text-gray-400">
                            Assign Room
                        </label>
                        <select name="room_id"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 transition focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-400/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="">Select a room…</option>
                            @foreach($availableRooms ?? [] as $room)
                                <option value="{{ $room->id }}">{{ $room->room_no }} — {{ $room->room_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-widest text-gray-400">
                            Notes (optional)
                        </label>
                        <textarea name="notes" rows="2"
                            placeholder="Any notes for this confirmation…"
                            class="w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 transition focus:border-green-400 focus:outline-none focus:ring-2 focus:ring-green-400/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                    <button type="button" @click="assignModal=false"
                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-green-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-green-600 active:scale-95">
                        Confirm Booking
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         CANCEL MODAL
    ══════════════════════════════════════════ --}}
    <div x-show="cancelModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.away="cancelModal=false"
            class="w-full max-w-sm rounded-3xl bg-white shadow-2xl dark:bg-gray-900"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="rounded-t-3xl bg-amber-500 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Cancel Booking?</h3>
                        <p class="mt-0.5 text-xs text-amber-100" x-text="'Ref: ' + selectedRef"></p>
                    </div>
                    <button @click="cancelModal=false"
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <form method="POST"
                x-bind:action="">
                @csrf
                @method('PATCH')

                <div class="px-6 py-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        This will mark the booking as <span class="font-semibold text-amber-600">cancelled</span>. The guest will be notified.
                    </p>
                    <div class="mt-4">
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-widest text-gray-400">
                            Reason (optional)
                        </label>
                        <textarea name="cancel_reason" rows="2"
                            placeholder="Reason for cancellation…"
                            class="w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 transition focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                    <button type="button" @click="cancelModal=false"
                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400">
                        Go Back
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-amber-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-amber-600 active:scale-95">
                        Yes, Cancel
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         DELETE MODAL
    ══════════════════════════════════════════ --}}
    <div x-show="deleteModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.away="deleteModal=false"
            class="w-full max-w-sm rounded-3xl bg-white shadow-2xl dark:bg-gray-900"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="rounded-t-3xl bg-red-500 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Delete Booking?</h3>
                        <p class="mt-0.5 text-xs text-red-100" x-text="'Ref: ' + selectedRef"></p>
                    </div>
                    <button @click="deleteModal=false"
                        class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <form method="POST"
                x-bind:action="">
                @csrf
                @method('DELETE')

                <div class="px-6 py-5">
                    <div class="flex items-start gap-3 rounded-xl bg-red-50 p-4 dark:bg-red-400/10">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <p class="text-sm text-red-700 dark:text-red-300">
                            This action <span class="font-bold">cannot be undone.</span> The booking record will be permanently deleted.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                    <button type="button" @click="deleteModal=false"
                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400">
                        Cancel
                    </button>
                    <button type="submit"
                        class="rounded-xl bg-red-500 px-5 py-2 text-sm font-semibold text-white transition hover:bg-red-600 active:scale-95">
                        Delete Permanently
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

{{-- Live search --}}
<script>
    function filterTable() {
        const query = document.getElementById('search-input').value.toLowerCase();
        document.querySelectorAll('.booking-row').forEach(row => {
            const text = row.dataset.search || '';
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }
</script>

@endsection

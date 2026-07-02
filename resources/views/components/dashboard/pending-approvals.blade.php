@props(['pendingBookings'])

<div x-data="{
    open: false,
    selected: {},
    openModal(booking) {
        this.selected = booking;
        this.open = true;
    },
    closeModal() {
        this.open = false;
        this.selected = {};
    }
}" class="col-span-12 md:col-span-6">
    <div class="flex h-full flex-col rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-sm transition hover:border-white/20">
        <div class="mb-5 flex items-start justify-between">
            <div>
                <h3 class="font-semibold text-gray-600 dark:text-white">  Pending Approvals <span class="ml-2 inline-flex items-center rounded-full bg-yellow-500/10 px-2 py-0.5 text-l font-medium text-yellow-400">{{ $pendingBookings->count() }}</span>

                </h3>
                <p class="mt-1 text-sm text-gray-500">Bookings awaiting your action.</p>
            </div>
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-500/10">
                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            @forelse ($pendingBookings as $pendingBooking)
                <div class="rounded-2xl border border-yellow-500/20 bg-yellow-500/10 p-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-yellow-400">{{ $pendingBooking->room_type ?? 'Room Booking' }}</p>
                            <h4 class="mt-1 font-semibold text-gray-600 dark:text-white">{{ $pendingBooking->reference_number ?? 'Pending Booking' }} &bull; {{ optional($pendingBooking->user)->name ?? 'User' }}</h4>
                            <p class="mt-1 text-sm text-gray-400">{{ optional($pendingBooking->check_in)->format('M. j, Y') ?? 'TBD' }} to {{ optional($pendingBooking->check_out)->format('M. j, Y') ?? 'TBD' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <button type="button"
                                class="rounded-xl bg-amber-200 dark:bg-white/5 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-200 transition dark:hover:bg-white/10 hover:bg-amber-300"
                                @click="openModal(@js([
                                    'id' => $pendingBooking->id,
                                    'reference' => $pendingBooking->reference_number ?? 'Pending Booking',
                                    'guestName' => optional($pendingBooking->user)->name ?? 'Guest',
                                    'roomType' => $pendingBooking->room_type ?? 'Room Booking',
                                    'numberOfGuests' => (int) ($pendingBooking->number_of_guests ?? 1),
                                    'nights' => max(1, optional($pendingBooking->check_in)->diffInDays($pendingBooking->check_out) ?? 1),
                                    'roomPrice' => number_format((float) ($pendingBooking->room_price ?? 0), 2, '.', ''),
                                    'microPricingAmount' => number_format((float) ($pendingBooking->micro_pricing_amount ?? 0), 2, '.', ''),
                                    'ambiancePrice' => number_format((float) ($pendingBooking->ambiance_price ?? 0), 2, '.', ''),
                                    'foodPrice' => number_format((float) ($pendingBooking->food_price ?? 0), 2, '.', ''),
                                    'totalPrice' => number_format((float) ($pendingBooking->total_price ?? 0), 2, '.', ''),
                                    'floorLevel' => $pendingBooking->floor_level ?? 'No preference',
                                    'ambiance' => $pendingBooking->ambiance ?? 'Regular Room',
                                    'foodPackage' => $pendingBooking->food_package ?? 'No Food',
                                    'specialRequests' => $pendingBooking->remarks ?? '—',
                                    'checkIn' => optional($pendingBooking->check_in)->format('M. j, Y') ?? '',
                                    'checkOut' => optional($pendingBooking->check_out)->format('M. j, Y') ?? '',
                                    'bookedAt' => optional($pendingBooking->created_at)->format('M j, Y, g:i A') ?? '',
                                    'status' => 'Pending',
                                ]))">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.04] p-4 text-gray-500">No pending approvals at the moment.</div>
            @endforelse
        </div>

        <div
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="closeModal()"
                x-show="open"
                class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-900"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <div class="flex items-start justify-between bg-gradient-to-br from-amber-600 to-yellow-300 px-6 py-4">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-widest text-slate-700/70">Reference</p>
                        <h3 class="mt-0.5 font-mono text-base font-bold text-slate-900" x-text="selected.reference || '—'"></h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-full bg-black/10 px-2.5 py-1 text-xs font-semibold text-slate-800">
                            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-slate-800"></span>
                            <span x-text="selected.status || 'Pending'"></span>
                        </span>
                        <button
                            @click="closeModal()"
                            class="rounded-full p-1.5 text-slate-800 transition hover:bg-black/10"
                            aria-label="Close"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 px-6 py-5">
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 rounded-xl bg-yellow-50 px-3 py-2.5 dark:bg-yellow-400/10">
                            <span
                                class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-yellow-400/30">
                                <svg class="h-3.5 w-3.5 text-yellow-700 dark:text-white" fill="none" stroke="currentColor"
                                    stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-amber-900 dark:text-white" x-text="selected.roomType || '—'"></p>
                                <p class="text-xs text-amber-700/70 dark:text-white" x-text="selected.guestName || '—'"></p>
                            </div>
                            <span class="flex-shrink-0 rounded-full bg-amber-200/70 px-2 py-0.5 text-xs font-semibold text-amber-800" x-text="(selected.nights || '—') + 'N'"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/2">
                                <p class="text-xs text-slate-400">Check-in</p>
                                <p class="mt-0.5 text-sm font-semibold text-gray-800 dark:text-white " x-text="selected.checkIn || '—'"></p>
                            </div>
                            <div class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/2">
                                <p class="text-xs text-slate-400">Check-out</p>
                                <p class="mt-0.5 text-sm font-semibold text-gray-800 dark:text-white" x-text="selected.checkOut || '—'"></p>
                            </div>
                        </div>

                        <div class="rounded-xl border border-yellow-100 bg-yellow-50/50 px-3 py-3 dark:border-yellow-400/10 dark:bg-yellow-400/5">
                            <div class="space-y-1.5 text-sm">
                                <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                    <span>Room rate</span> x <span x-text="selected.nights || '—'"></span> night(s)
                                    <span x-text="selected.roomPrice ? '₱' + selected.roomPrice : '—'"></span>
                                </div>
                                <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                    <span>Add-ons</span>
                                    <span x-text="selected.microPricingAmount ? '₱' + selected.microPricingAmount : '₱0.00'"></span>
                                </div>
                                <div class="flex justify-between border-t border-yellow-200/60 pt-1.5">
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Total</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="selected.totalPrice ? '₱' + selected.totalPrice : '—'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <p class="mb-1 text-xs font-semibold uppercase tracking-widest text-gray-400">Stay Preferences</p>

                        <div class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/5">
                            <span
                                class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-400/15">
                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-gray-400 dark:text-gray-500">Floor Level</p>
                                <p class="text-sm font-semibold text-gray-800 dark:text-white" x-text="selected.floorLevel || 'No preference'"></p>
                            </div>
                            <span class="flex-shrink-0 rounded-full bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700">Free</span>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/5">
                            <span
                                class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-400/15">
                                <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3v2.25m6.364.386-1j.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-gray-400 dark:text-gray-500">Ambiance</p>
                                <p class="text-sm font-semibold text-gray-800 dark:text-white" x-text="selected.ambiance || 'Regular Room'"></p>
                            </div>
                            <span class="flex-shrink-0 rounded-full bg-purple-100 px-2 py-0.5 text-xs font-semibold text-purple-700" x-text="selected.ambiancePrice ? '+₱' + selected.ambiancePrice : 'Base'"></span>
                        </div>

                        <div class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/5">
                            <span
                                class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-400/15">
                                <svg class="h-4 w-4 text-orange-600 dark:text-orange-400" fill="none"
                                    stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21M6.375 6.375 5.25 5.25m13.5 1.125-1.125-1.125m1.125 13.5-1.125-1.125M6.375 17.625 5.25 18.75M3 12a9 9 0 1 1 18 0 9 9 0 0 1-18 0Z" />
                                </svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs text-gray-400 dark:text-gray-500">Food Package</p>
                                <p class="text-sm font-semibold text-gray-800 dark:text-white" x-text="selected.foodPackage || 'No Food'"></p>
                            </div>
                            <span class="flex-shrink-0 rounded-full bg-orange-100 px-2 py-0.5 text-xs font-semibold text-orange-700" x-text="selected.foodPrice ? '+₱' + selected.foodPrice : 'Free'"></span>
                        </div>

                        <div x-show="selected.specialRequests && selected.specialRequests !== '—'" class="rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5 dark:border-gray-800 dark:bg-white/5">
                            <p class="text-xs text-gray-400 dark:text-gray-500">Special Requests</p>
                            <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-300" x-text="selected.specialRequests"></p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-gray-100 px-6 py-3 dark:border-gray-800">
                    <p class="text-xs text-gray-400 dark:text-gray-500">Booked <span class="text-gray-500 dark:text-gray-300" x-text="selected.bookedAt || '—'"></span></p>
                    <button
                        @click="closeModal()"
                        class="rounded-xl border border-gray-200 px-4 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-white/5"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

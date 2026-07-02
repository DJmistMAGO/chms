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
                <h3 class="font-semibold text-gray-600 dark:text-white">Pending Approvals</h3>
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
                                class="rounded-xl bg-white/5 px-3 py-1.5 text-xs font-semibold text-gray-200 transition hover:bg-white/10"
                                @click="openModal({
                                    id: {{ $pendingBooking->id }},
                                    reference: '{{ addslashes($pendingBooking->reference_number ?? 'Pending Booking') }}',
                                    user: '{{ addslashes(optional($pendingBooking->user)->name ?? 'User') }}',
                                    roomType: '{{ addslashes($pendingBooking->room_type ?? 'Room Booking') }}',
                                    checkIn: '{{ optional($pendingBooking->check_in)->format('M. j, Y') ?? '' }}',
                                    checkOut: '{{ optional($pendingBooking->check_out)->format('M. j, Y') ?? '' }}'
                                })">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-white/[0.06] bg-white/[0.04] p-4 text-gray-500">No pending approvals at the moment.</div>
            @endforelse
        </div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4 py-6" x-transition>
            <div class="w-full max-w-xl rounded-3xl bg-white p-6 shadow-xl" @click.away="closeModal()">
                <div class="flex items-start justify-between">
                    <h3 class="text-lg font-semibold">Booking Details</h3>
                    <button @click="closeModal()" class="text-gray-500">&times;</button>
                </div>
                <div class="mt-4 space-y-2 text-sm text-gray-700">
                    <p><strong>Reference:</strong> <span x-text="selected.reference"></span></p>
                    <p><strong>Guest:</strong> <span x-text="selected.user"></span></p>
                    <p><strong>Room Type:</strong> <span x-text="selected.roomType"></span></p>
                    <p><strong>Dates:</strong> <span x-text="selected.checkIn + ' to ' + selected.checkOut"></span></p>
                </div>
                <div class="mt-6 flex justify-end">
                    <button @click="closeModal()" class="rounded-xl bg-gray-100 px-4 py-2 text-sm">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

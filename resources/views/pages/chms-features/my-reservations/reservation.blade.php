@extends('layouts.authenticated.app')

@section('title', 'My Reservations')

@section('content')
    {{-- <x-common.page-breadcrumb pageTitle="My Reservations" /> --}}

    <div class="rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">

        {{-- ── Page Header ── --}}
        <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">My Reservations</h2>
                <p class="mt-0.5 text-sm text-gray-400">Track and manage all your hotel bookings.</p>
            </div>
            <button type="button" onclick="openNewBookingModal()"
                class="inline-flex items-center gap-2 rounded-xl bg-amber-400 px-4 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-amber-300 active:bg-amber-500">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Booking
            </button>
        </div>

        <div id="newBookingModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-white dark:bg-black/60 backdrop-blur-sm px-4 py-6"
            onclick="if (event.target === this) closeNewBookingModal()"
            role="dialog" aria-modal="true" aria-labelledby="newBookingTitle">
            <div class="w-full max-w-2xl max-h-[88vh] flex flex-col overflow-hidden rounded-3xl bg-white dark:bg-[#12172a] border border-white/10 shadow-2xl">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-white/10 px-6 py-4">
                    <h3 id="newBookingTitle" class="text-lg font-semibold text-gray-900 dark:text-white">
                        <span id="newBookingHeaderText">Choose a room</span>
                    </h3>
                    <button type="button" onclick="closeNewBookingModal()" aria-label="Close"
                        class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-dark dark:hover:bg-white/10 hover:text-gray-900 dark:hover:text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="newBookingBody" class="flex-1 overflow-y-auto px-6 py-5">
                    <div id="roomPickerLoading" class="hidden py-16 text-center text-sm text-gray-500 dark:text-slate-400">Loading rooms…</div>
                    <div id="roomPickerList" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
                    <div id="wizardContainer" class="hidden"></div>
                </div>
            </div>
        </div>

        <script>
            const newBookingModal = document.getElementById('newBookingModal');
            const roomPickerList = document.getElementById('roomPickerList');
            const roomPickerLoading = document.getElementById('roomPickerLoading');
            const wizardContainer = document.getElementById('wizardContainer');
            const newBookingHeaderText = document.getElementById('newBookingHeaderText');

            const ROOM_OPTIONS_URL = '{{ route('reservations.booking.rooms') }}';
            const ROOM_WIZARD_URL_BASE = '{{ url('/my-reservations/new-booking') }}';

            let wizardLibsLoaded = false;

            function loadWizardLibs() {
                if (wizardLibsLoaded) return Promise.resolve();

                const css = document.createElement('link');
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/hotel-datepicker@4.12.2/dist/css/hotel-datepicker.min.css';
                document.head.appendChild(css);

                const loadScript = (src) => new Promise((resolve, reject) => {
                    const s = document.createElement('script');
                    s.src = src;
                    s.onload = resolve;
                    s.onerror = reject;
                    document.body.appendChild(s);
                });

                return loadScript('https://cdn.jsdelivr.net/npm/fecha@4.2.3/dist/fecha.min.js')
                    .then(() => loadScript('https://cdn.jsdelivr.net/npm/hotel-datepicker@4.12.2/dist/js/hotel-datepicker.min.js'))
                    .then(() => { wizardLibsLoaded = true; });
            }

            function openNewBookingModal() {
                newBookingModal.classList.remove('hidden');
                newBookingModal.classList.add('flex');
                showRoomPicker();
            }

            function closeNewBookingModal() {
                newBookingModal.classList.add('hidden');
                newBookingModal.classList.remove('flex');
            }

            function showRoomPicker() {
                newBookingHeaderText.textContent = 'Choose a room';
                wizardContainer.classList.add('hidden');
                wizardContainer.innerHTML = '';
                roomPickerList.classList.remove('hidden');

                if (roomPickerList.dataset.loaded === '1') return;

                roomPickerLoading.classList.remove('hidden');
                roomPickerList.innerHTML = '';

                fetch(ROOM_OPTIONS_URL, { headers: { 'Accept': 'application/json' } })
                    .then(res => res.json())
                    .then(data => {
                        roomPickerLoading.classList.add('hidden');
                        roomPickerList.dataset.loaded = '1';

                        data.rooms.forEach(room => {
                            const card = document.createElement('button');
                            card.type = 'button';
                            card.className = 'text-left rounded-2xl border border-gray-300 dark:border-white/10 bg-gray-100 dark:bg-white/5 overflow-hidden transition hover:border-amber-400/60 hover:bg-white/[0.08] hover:dark:bg-white/10 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2';
                            card.onclick = () => loadWizard(room.slug, room.name);
                            card.innerHTML = `
                                <div class="h-28 w-full bg-cover bg-center" style="background-image:url('${room.image}')"></div>
                                <div class="p-4">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">${room.name}</p>
                                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-400">Up to ${room.capacity} guests</p>
                                    <p class="mt-2 text-sm font-semibold text-amber-400">₱${room.price.toLocaleString()} <span class="text-xs font-normaln text-gray-900 dark:text-slate-400">/night</span></p>
                                </div>
                            `;
                            roomPickerList.appendChild(card);
                        });
                    })
                    .catch(() => {
                        roomPickerLoading.textContent = 'Could not load rooms. Please try again.';
                    });
            }

            function loadWizard(slug, name) {
                newBookingHeaderText.textContent = name;
                roomPickerList.classList.add('hidden');
                wizardContainer.classList.remove('hidden');
                wizardContainer.innerHTML = '<div class="py-16 text-center text-sm text-slate-400">Loading…</div>';

                Promise.all([
                    loadWizardLibs(),
                    fetch(`${ROOM_WIZARD_URL_BASE}/${slug}`, { headers: { 'Accept': 'text/html' } }).then(r => r.text()),
                ]).then(([, html]) => {
                    wizardContainer.innerHTML = html;
                    executeInjectedScripts(wizardContainer);
                });
            }


            function executeInjectedScripts(container) {
                const scripts = container.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.textContent = oldScript.textContent;
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
            }

            function backToRoomPicker() {
                showRoomPicker();
            }
        </script>

        {{-- ── Status Tabs ── --}}
        <div class="mb-6 flex gap-2 border-b border-gray-100 dark:border-gray-800" id="status-tabs">

            <button onclick="switchTab('pending')" id="tab-pending"
                class="tab-btn relative pb-3 px-1 text-sm font-semibold transition-colors text-amber-700 dark:text-yellow-500">
                <span class="flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400 dark:bg-yellow-400"></span>
                    Pending
                    <span class="rounded-full bg-yellow-400/20 px-1.5 py-0.5 text-xs font-bold text-yellow-600">
                        {{ $pendingBookings->count() }}
                    </span>
                </span>
            </button>

            <button onclick="switchTab('confirmed')" id="tab-confirmed"
                class="tab-btn relative pb-3 px-1 text-sm font-medium text-gray-400 transition-colors hover:text-gray-600 dark:hover:text-gray-300">
                <span class="flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-400 dark:bg-green-400"></span>
                    Confirmed
                    <span class="rounded-full bg-green-400/15 px-1.5 py-0.5 text-xs font-bold text-green-600">
                        {{ $confirmedBookings->count() }}
                    </span>
                </span>
            </button>

        </div>

        {{-- ── Alpine.js Modal Component Container ── --}}
<div x-data="bookingDetailsModal()"
     x-show="isOpen"
     x-cloak
     @open-booking-modal.window="openModal($event.detail)"
     @keydown.escape.window="closeModal()"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4 py-6"
     style="display: none;">

    <div @click.away="closeModal()"
         class="w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden rounded-3xl bg-white dark:bg-[#12172a] border border-gray-200 dark:border-white/10 shadow-2xl transition-all">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-white/10 px-6 py-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Reservation Details</h3>
                <p class="text-xs text-gray-400" x-text="'Ref: ' + (booking.reference_number || 'N/A')"></p>
            </div>
            <button type="button" @click="closeModal()"
                class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-gray-100 dark:hover:bg-white/10 hover:text-gray-900 dark:hover:text-white">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="flex-1 overflow-y-auto px-6 py-5 space-y-6">

            {{-- Core Status & Pricing Summary --}}
            <div class="flex items-center justify-between rounded-2xl bg-amber-500/10 dark:bg-amber-400/5 p-4 border border-amber-500/20">
                <div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Status</span>
                    <p class="text-sm font-bold capitalize text-amber-600 dark:text-amber-400" x-text="booking.status"></p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Total Price</span>
                    <p class="text-lg font-black text-gray-900 dark:text-white" x-text="'₱' + formatNumber(booking.total_price)"></p>
                </div>
            </div>

            {{-- Grid of Attributes --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <div class="rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Room No.</p>
                    <p class="font-semibold text-gray-800 dark:text-white" x-text="booking.room_id || 'N/A'"></p>
                </div>
                <div class="rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Room Type</p>
                    <p class="font-semibold text-gray-800 dark:text-white" x-text="booking.room_type || 'N/A'"></p>
                </div>
                <div class="rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Floor Level</p>
                    <p class="font-semibold text-gray-800 dark:text-white" x-text="booking.floor_level || 'N/A'"></p>
                </div>
                <div class="rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Ambiance</p>
                    <p class="font-semibold text-gray-800 dark:text-white" x-text="booking.ambiance || 'N/A'"></p>
                </div>
                <div class="rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Food Package</p>
                    <p class="font-semibold text-gray-800 dark:text-white" x-text="booking.food_package || 'None'"></p>
                </div>
                <div class="rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Guests</p>
                    <p class="font-semibold text-gray-800 dark:text-white" x-text="(booking.number_of_guests || 0) + ' Guest(s)'"></p>
                </div>
                <div class="rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Check In</p>
                    <p class="font-semibold text-gray-800 dark:text-white" x-text="booking.check_in || 'N/A'"></p>
                </div>
                <div class="rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Check Out</p>
                    <p class="font-semibold text-gray-800 dark:text-white" x-text="booking.check_out || 'N/A'"></p>
                </div>
                <div class="rounded-xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-xs text-gray-400">Expires At</p>
                    <p class="font-semibold text-rose-500" x-text="booking.expires_at || 'N/A'"></p>
                </div>
            </div>

            {{-- Breakdown Pricing --}}
            <div class="rounded-2xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-4 space-y-2 text-xs">
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>Base Room Price</span>
                    <span class="font-semibold text-gray-900 dark:text-white" x-text="'₱' + formatNumber(booking.room_price)"></span>
                </div>
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>Micro Pricing Amount</span>
                    <span class="font-semibold text-gray-900 dark:text-white" x-text="'₱' + formatNumber(booking.micro_pricing_amount)"></span>
                </div>
            </div>

            {{-- Remarks --}}
            <div class="rounded-2xl border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 p-4">
                <p class="text-xs text-gray-400 mb-1">Remarks</p>
                <p class="text-sm text-gray-700 dark:text-gray-300 italic" x-text="booking.remarks || 'No remarks provided.'"></p>
            </div>
        </div>
    </div>
</div>

        {{-- ── PENDING PANEL ── --}}
        <div id="panel-pending">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($pendingBookings as $booking)
                    @include('pages.chms-features.my-reservations.partials.booking-card', [
                        'booking'      => $booking,
                        'statusLabel'  => 'Pending',
                        'statusColor'  => 'yellow',
                        'pulse'        => true,
                    ])
                @empty
                    @include('pages.chms-features.my-reservations.partials.booking-empty', ['label' => 'pending'])
                @endforelse
            </div>
        </div>

        {{-- ── CONFIRMED PANEL ── --}}
        <div id="panel-confirmed" class="hidden">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($confirmedBookings as $booking)
                    @include('pages.chms-features.my-reservations.partials.booking-card', [
                        'booking'      => $booking,
                        'statusLabel'  => 'Confirmed',
                        'statusColor'  => 'green',
                  'pulse'        => false,
                    ])
                @empty
                    @include('pages.chms-features.my-reservations.partials.booking-empty', ['label' => 'confirmed'])
                @endforelse
            </div>
        </div>

    </div>


    <script>
    function bookingDetailsModal() {
        return {
            isOpen: false,
            booking: {},
            openModal(data) {
                this.booking = data;
                this.isOpen = true;
            },
            closeModal() {
                this.isOpen = false;
            },
            formatNumber(val) {
                return Number(val || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        }
    }
</script>

    <script>
        function switchTab(tab) {
            // Panels
            document.getElementById('panel-pending').classList.toggle('hidden', tab !== 'pending');
            document.getElementById('panel-confirmed').classList.toggle('hidden', tab !== 'confirmed');

            // Tab styles
            const tabs = { pending: 'yellow', confirmed: 'green' };

            Object.entries(tabs).forEach(([key, color]) => {
                const btn = document.getElementById('tab-' + key);
                if (key === tab) {
                    btn.classList.add('font-semibold', 'text-' + color + '-500');
                    btn.classList.remove('font-medium', 'text-gray-400');
                    btn.style.setProperty('--tw-scale-x', '1');
                    btn.querySelector('span.after\\:scale-x-0')?.classList.remove('after:scale-x-0');
                } else {
                    btn.classList.remove('font-semibold', 'text-' + color + '-500');
                    btn.classList.add('font-medium', 'text-gray-400');
                }
            });

            document.querySelectorAll('.tab-btn').forEach(b => b.style.borderBottom = '');
            const active = document.getElementById('tab-' + tab);
            const colorMap = { pending: '#facc15', confirmed: '#4ade80' };
            active.style.borderBottom = '2px solid ' + colorMap[tab];
        }

        // Init super
        document.getElementById('tab-pending').style.borderBottom  = '2px solid #facc15';
        document.getElementById('tab-confirmed').style.borderBottom = '';
    </script>

@endsection

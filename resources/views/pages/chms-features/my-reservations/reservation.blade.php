@extends('layouts.authenticated.app')

@section('title', 'My Reservations')

@section('content')
    <x-common.page-breadcrumb pageTitle="My Reservations" />

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
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 py-6"
            onclick="if (event.target === this) closeNewBookingModal()"
            role="dialog" aria-modal="true" aria-labelledby="newBookingTitle">
            <div class="w-full max-w-2xl max-h-[88vh] flex flex-col overflow-hidden rounded-3xl bg-[#12172a] border border-white/10 shadow-2xl">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-white/10 px-6 py-4">
                    <h3 id="newBookingTitle" class="text-lg font-semibold text-white">
                        <span id="newBookingHeaderText">Choose a room</span>
                    </h3>
                    <button type="button" onclick="closeNewBookingModal()" aria-label="Close"
                        class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-white/10 hover:text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body — swapped between room picker and wizard --}}
                <div id="newBookingBody" class="flex-1 overflow-y-auto px-6 py-5">
                    <div id="roomPickerLoading" class="hidden py-16 text-center text-sm text-slate-400">Loading rooms…</div>
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
                            card.className = 'text-left rounded-2xl border border-white/10 bg-white/5 overflow-hidden transition hover:border-amber-400/60 hover:bg-white/[0.08]';
                            card.onclick = () => loadWizard(room.slug, room.name);
                            card.innerHTML = `
                                <div class="h-28 w-full bg-cover bg-center" style="background-image:url('${room.image}')"></div>
                                <div class="p-4">
                                    <p class="text-sm font-semibold text-white">${room.name}</p>
                                    <p class="mt-1 text-xs text-slate-400">Up to ${room.capacity} guests</p>
                                    <p class="mt-2 text-sm font-semibold text-amber-400">₱${room.price.toLocaleString()} <span class="text-xs font-normal text-slate-400">/night</span></p>
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
                class="tab-btn relative pb-3 px-1 text-sm font-semibold transition-colors text-yellow-500">
                <span class="flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-400"></span>
                    Pending
                    <span class="rounded-full bg-yellow-400/20 px-1.5 py-0.5 text-xs font-bold text-yellow-600">
                        {{ $pendingBookings->count() }}
                    </span>
                </span>
            </button>

            <button onclick="switchTab('confirmed')" id="tab-confirmed"
                class="tab-btn relative pb-3 px-1 text-sm font-medium text-gray-400 transition-colors hover:text-gray-600 dark:hover:text-gray-300">
                <span class="flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-400"></span>
                    Confirmed
                    <span class="rounded-full bg-green-400/15 px-1.5 py-0.5 text-xs font-bold text-green-600">
                        {{ $confirmedBookings->count() }}
                    </span>
                </span>
            </button>

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

            // Active underline via direct style (simpler than Tailwind JIT)
            document.querySelectorAll('.tab-btn').forEach(b => b.style.borderBottom = '');
            const active = document.getElementById('tab-' + tab);
            const colorMap = { pending: '#facc15', confirmed: '#4ade80' };
            active.style.borderBottom = '2px solid ' + colorMap[tab];
        }

        // Init
        document.getElementById('tab-pending').style.borderBottom  = '2px solid #facc15';
        document.getElementById('tab-confirmed').style.borderBottom = '';
    </script>

@endsection

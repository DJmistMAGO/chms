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
            <a href=""
                class="inline-flex w-fit items-center gap-2 rounded-xl bg-yellow-400 px-4 py-2.5 text-xs font-semibold text-gray-900 transition hover:bg-yellow-500 active:scale-95">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Booking
            </a>
        </div>

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

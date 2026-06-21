@props(['rooms', 'allBookings', 'totalRooms', 'availableRooms'])

<div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-sm backdrop-blur-sm">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-start gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-300 to-yellow-600 shadow-lg shadow-amber-500/30">
                @if (auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="h-full w-full rounded-2xl object-cover">
                @else
                    <span class="text-lg font-bold text-white">{{ auth()->user()->getInitialsAttribute() }}</span>
                @endif
            </div>
            <div>
                <p class="text-sm text-black dark:text-gray-400">Logged in as, <span class="font-medium text-black dark:text-white">{{ auth()->user()->name }}</span></p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-600 dark:text-white">Staff Dashboard</h2>
                <p class="mt-1 max-w-2xl text-xs leading-relaxed text-gray-500">Manage rooms, bookings, check-ins, and approvals efficiently.</p>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-3 gap-4 sm:grid-cols-3 xl:grid-cols-5">
        <div class="rounded-2xl border border-gray/5 bg-white/[0.05] px-4 py-4 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-black dark:text-gray-400">Total Rooms</p>
            <h4 class="mt-2 text-2xl font-bold text-gray-600 dark:text-white">{{ $totalRooms }}</h4>
        </div>
        <div class="rounded-2xl border border-green-500/10 bg-green-500/10 px-4 py-4 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-green-600 dark:text-green-400">Available</p>
            <h4 class="mt-2 text-2xl font-bold text-green-600 dark:text-green-300">{{ $availableRooms }}</h4>
        </div>
        <div class="rounded-2xl border border-blue-500/10 bg-blue-500/10 px-4 py-4 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-400">Bookings Today</p>
            <h4 class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-300">{{ $allBookings->where('verified_at', today())->count() }}</h4>
        </div>
        <div class="rounded-2xl border border-yellow-500/10 bg-yellow-500/10 px-4 py-4 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-yellow-400">Pending</p>
            <h4 class="mt-2 text-2xl font-bold text-yellow-600 dark:text-yellow-300">{{ $allBookings->where('status', 'Pending')->count() }}</h4>
        </div>
        <div class="rounded-2xl border border-indigo-500/10 bg-indigo-500/10 px-4 py-4 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-indigo-400">Checked-in</p>
            <h4 class="mt-2 text-2xl font-bold text-indigo-600 dark:text-indigo-300">{{ $allBookings->where('status', 'Confirmed')->count() }}</h4>
        </div>
    </div>
</div>

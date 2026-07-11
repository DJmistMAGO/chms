@props([
    'totalGuests' => 0,
    'activeGuests' => 0,
    'totalBookings' => 0,
    'totalRevenue' => 0,
])

<div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-sm backdrop-blur-sm mb-6">
    <div class="mt-6 grid grid-cols-4 gap-4 sm:grid-cols-4 xl:grid-cols-4">
        <div class="rounded-2xl border border-gray/5 bg-white/[0.05] px-4 py-4 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-black dark:text-gray-400">Total Guests</p>
            <h4 class="mt-2 text-2xl font-bold text-gray-600 dark:text-white">{{ $totalGuests }}</h4>
        </div>

        <div class="rounded-2xl border border-green-500/10 bg-green-500/10 px-4 py-4 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-green-600 dark:text-green-400">Active Guests</p>
            <h4 class="mt-2 text-2xl font-bold text-green-600 dark:text-green-300">{{ $activeGuests }}</h4>
        </div>

        <div class="rounded-2xl border border-blue-500/10 bg-blue-500/10 px-4 py-4 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">Total Bookings</p>
            <h4 class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-300">{{ $totalBookings }}</h4>
        </div>

        <div class="rounded-2xl border border-yellow-500/10 bg-yellow-500/10 px-4 py-4 text-center">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-yellow-700 dark:text-yellow-400">Total Revenue</p>
            <h4 class="mt-2 text-2xl font-bold text-yellow-700 dark:text-yellow-300"> ₱ {{ number_format($totalRevenue, 2) }}</h4>
        </div>
    </div>
</div>

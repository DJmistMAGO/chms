@props(['bookingStats' => []])

<div class="rounded-3xl border border-gray-200 dark:border-white/10 bg-white/[0.03] p-6 shadow-lg backdrop-blur-lg hover:border-gray-300 transition">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-yellow-300 shadow-sm shadow-amber-500/30">
                @if (auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="h-full w-full rounded-2xl object-cover">
                @else
                    <span class="text-lg font-bold text-white">{{ auth()->user()->getInitialsAttribute() }}</span>
                @endif
            </div>

            <div>
                <p class="text-sm text-black dark:text-gray-400">Welcome back, <span class="font-medium">{{ auth()->user()->name }}!</span></p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Your Booking Dashboard</h2>
                <p class="mt-1 text-xs text-gray-500">Track reservations, room status, and recent activity.</p>
            </div>
        </div>

        <div class="flex shrink-0 gap-3">
            @foreach ($bookingStats as $label => $value)
                <div class="rounded-2xl bg-{{ strtolower($label) === 'active' ? 'green' : (strtolower($label) === 'done' ? 'blue' : 'yellow') }}-500/10 px-5 py-3 text-center">
                    <p class="text-xs font-semibold uppercase tracking-widest text-{{ strtolower($label) === 'active' ? 'green' : (strtolower($label) === 'done' ? 'blue' : 'yellow') }}-400">{{ $label }}</p>
                    <h4 class="mt-1 text-2xl font-bold text-{{ strtolower($label) === 'active' ? 'green' : (strtolower($label) === 'done' ? 'blue' : 'yellow') }}-400">{{ $value }}</h4>
                </div>
            @endforeach
        </div>
    </div>
</div>

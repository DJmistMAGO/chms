<div class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-200 py-14 dark:border-gray-700">
    <span class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-yellow-50 dark:bg-yellow-400/10">
        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
        </svg>
    </span>
    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No {{ $label }} reservations</p>
    <p class="mt-1 text-xs text-gray-400">Ready to book your next stay?</p>
    <button type="button" onclick="openNewBookingModal()"
        class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-yellow-400 px-4 py-2 text-xs font-semibold text-gray-900 transition hover:bg-yellow-500">
        Browse Rooms
    </button>
</div>

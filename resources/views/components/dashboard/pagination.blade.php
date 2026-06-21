@props(['rooms'])

<div class="mt-6 flex flex-col gap-4 border-t border-white/[0.06] pt-4 sm:flex-row sm:items-center sm:justify-between">

    @php
        $roomsTotal = $rooms->total();
        $roomsFirst = $rooms->firstItem();
        $roomsLast = $rooms->lastItem();
    @endphp

    <p class="text-sm text-gray-500">
        Showing
        <span class="font-medium text-black dark:text-white">{{ $roomsFirst }}</span>
        to
        <span class="font-medium text-black dark:text-white">{{ $roomsLast }}</span>
        of
        <span class="font-medium text-black font-black italic dark:text-white mr-1">{{ $roomsTotal }}</span>
        rooms
    </p>

    <div class="flex items-center gap-2">
        @if ($rooms->hasPages())

            @if ($rooms->onFirstPage())
                <span class="inline-flex items-center rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2 text-sm text-gray-500 opacity-50 cursor-not-allowed">
                    ‹
                </span>
            @else
                <a href="{{ $rooms->previousPageUrl() }}" class="inline-flex items-center rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2 text-sm text-gray-400 transition hover:bg-white/[0.06] hover:text-gray-700">
                    ‹
                </a>
            @endif

            @for ($page = 1; $page <= $rooms->lastPage(); $page++)
                @if ($page == $rooms->currentPage())
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500 text-sm font-semibold text-white shadow-lg shadow-amber-500/20">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $rooms->url($page) }}" class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/[0.03] text-sm text-gray-400 transition hover:bg-white/[0.06] hover:text-gray-700">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if ($rooms->hasMorePages())
                <a href="{{ $rooms->nextPageUrl() }}" class="inline-flex items-center rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2 text-sm text-gray-400 transition hover:bg-white/[0.06] hover:text-gray-700">
                    ›
                </a>
            @else
                <span class="inline-flex items-center rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2 text-sm text-gray-500 opacity-50 cursor-not-allowed">
                    ›
                </span>
            @endif

        @endif
    </div>
</div>

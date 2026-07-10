@extends('layouts.authenticated.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Guest Management" />

    @if (session('success'))
        <div id="flash-success"
            class="mb-4 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12l5 5L20 7" />
            </svg>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').remove()"
                    class="ml-auto text-green-500 hover:text-green-700 dark:hover:text-green-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">

        <div class="mb-5 flex items-center justify-between lg:mb-7">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Guest Management
            </h3>
            <span class="text-sm text-gray-400 dark:text-gray-500">{{ $guests->total() }} guest{{ $guests->total() === 1 ? '' : 's' }}</span>
        </div>

        <div class="mb-5 relative">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z" />
            </svg>
            <input type="text" id="search-input" placeholder="Search by name, reference, room type…"
                oninput="filterTable()"
                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-700 transition focus:border-yellow-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white">
        </div>

        @if ($guests->isEmpty())
            <div class="flex flex-col items-center gap-2 py-16">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-50 dark:bg-yellow-400/10">
                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </span>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No guest records found.</p>
                <p class="text-xs text-gray-400">All caught up! No guest records to display.</p>
            </div>
        @else
            <div id="guest-grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($guests as $guest)
                    @php
                        $guestStatus = strtolower($guest['status'] ?? 'inactive');
                        $isProtected = !empty($guest['roles']) && count(array_intersect($guest['roles'], ['admin', 'staff'])) > 0;
                        $upcoming = $guest['upcoming_booking'] ?? null;
                        $idStatusStyles = [
                            'verified' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                            'pending'  => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            'rejected' => 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        ];
                        $idStatus = $guest['valid_id_status'] ?? 'pending';
                        $idStatusClass = $idStatusStyles[$idStatus] ?? $idStatusStyles['pending'];
                    @endphp

                        <div class="guest-row group flex flex-col rounded-2xl border border-gray-200 bg-white transition hover:shadow-md dark:border-gray-800 dark:bg-white/[0.02]" data-search="
                        {{ strtolower($guest['name']) }}
                        {{ strtolower($guest['email']) }}
                        {{ strtolower($guest['phone'] ?? '') }}
                        {{ strtolower($guest['address'] ?? '') }}
                        {{ strtolower($guest['valid_id_status'] ?? '') }}
                        @if ($guest['bookings'])
                            @foreach ($guest['bookings'] ?? [] as $booking)
                                {{ strtolower($booking['reference'] ?? '') }}
                                {{ strtolower($booking['room'] ?? '') }}
                                {{ strtolower($booking['status'] ?? '') }}
                                {{ strtolower($booking['check-in'] ?? '') }}
                                {{ strtolower($booking['check-out'] ?? '') }}
                                {{ strtolower($booking['total_amount'] ?? '') }}
                            @endforeach
                        @endif
                    ">

                        {{-- Card header --}}
                        <div class="flex items-start gap-3 px-5 pt-5">
                            @if($guest['avatar'])
                                <img src="{{ asset('storage/' . $guest['avatar']) }}" alt="{{ $guest['name'] }}'s Avatar"
                                    class="h-12 w-12 flex-shrink-0 rounded-full object-cover ring-2 ring-white dark:ring-gray-900">
                            @else
                                <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 font-serif text-base font-semibold text-amber-700 ring-2 ring-white dark:bg-amber-900/40 dark:text-amber-300 dark:ring-gray-900">
                                    {{ strtoupper(substr($guest['name'], 0, 2)) }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold text-gray-800 dark:text-white/90">{{ $guest['name'] }}</p>
                                <p class="truncate text-xs text-gray-400 dark:text-gray-500">{{ $guest['email'] }}</p>
                            </div>

                            <span class="inline-flex flex-shrink-0 items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $guestStatus === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200' : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-200' }}">
                                {{ $guestStatus === 'active' ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        {{-- Contact --}}
                        <div class="mt-4 space-y-2 px-5 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="truncate">{{ $guest['phone'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">{{ $guest['address'] ?? 'N/A' }}</span>
                            </div>
                        </div>

                        {{-- Booking summary --}}
                        <div class="mx-5 mt-4 rounded-xl bg-gray-50 px-4 py-3 dark:bg-white/[0.03]">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Bookings
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $guest['bookings_count'] ?? 0 }}</span>
                            </div>

                            @if ($upcoming)
                                <p class="mt-1.5 truncate text-xs text-gray-500 dark:text-gray-400">
                                    Upcoming · {{ $upcoming['check_in'] ?? '—' }}
                                    <span class="text-gray-300 dark:text-gray-600">→</span>
                                    {{ $upcoming['check_out'] ?? '—' }}
                                </p>
                            @else
                                <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500">No upcoming bookings</p>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="mt-4 flex items-center justify-between gap-2 border-t border-gray-100 px-5 py-3 dark:border-white/10">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $idStatusClass }}">
                                ID: {{ ucfirst($idStatus) }}
                            </span>

                            @if ($isProtected)
                                <span class="inline-flex items-center gap-1 rounded-lg bg-gray-500 px-3 py-1.5 text-xs font-medium text-white cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                    Protected
                                </span>
                            @else
                                <button type="button"
                                    onclick='openViewModal(@json($guest))'
                                    class="inline-flex items-center gap-1 rounded-lg bg-slate-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-slate-700 active:bg-slate-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path d="M2.05 12a9.95 9.95 0 0119.9 0 9.95 9.95 0 01-19.9 0z" />
                                    </svg>
                                    View
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        @if ($guests->hasPages())
            <div class="mt-6">
                {{ $guests->links() }}
            </div>
        @endif

         <div id="statusModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">

                <div id="modalIcon" class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full">
                </div>

                <h3 id="modalTitle" class="mb-2 text-center text-base font-semibold text-gray-800 dark:text-white/90"></h3>
                <p id="modalMessage" class="mb-6 text-center text-sm text-gray-500 dark:text-gray-400"></p>

                <div class="flex gap-3">
                    <button type="button"
                            onclick="closeStatusModal()"
                            class="flex-1 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                        Cancel
                    </button>

                    <form id="statusForm" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" id="modalConfirmBtn"
                                class="w-full rounded-lg px-4 py-2 text-sm font-medium text-white transition">
                            Confirm
                        </button>
                    </form>
                </div>

            </div>
        </div>

        {{-- Edit Modal (styled like View modal) --}}
        <div id="editModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-6"
            onclick="if (event.target === this) closeEditModal()"
            role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
            <div class="w-full max-w-2xl overflow-hidden rounded-3xl bg-white shadow-2xl dark:bg-gray-900">

                {{-- Header --}}
                <div class="relative bg-gradient-to-br from-amber-50 to-white px-6 pt-6 pb-5 dark:from-gray-800 dark:to-gray-900">
                    <button type="button" onclick="closeEditModal()" aria-label="Close"
                        class="absolute right-5 top-5 flex h-8 w-8 items-center justify-center rounded-full text-gray-400 transition hover:bg-black/5 hover:text-gray-600 dark:hover:bg-white/10 dark:hover:text-gray-300">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-4">
                        <div id="editAvatar" class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-100 font-serif text-xl font-semibold text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-amber-600 dark:text-amber-400">Edit Guest</p>
                            <h3 id="editModalTitle" class="font-serif text-2xl font-semibold text-gray-900 dark:text-white">Edit guest</h3>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="px-6 py-6">
                    <form id="editForm" method="POST" class="grid grid-cols-1 gap-5 sm:grid-cols-2" data-confirm-leave>
                        @csrf
                        {{-- @method('PUT') --}}

                        <div>
                            <label for="editName" class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Name</label>
                            <input id="editName" name="name" type="text" class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" />
                        </div>

                        <div>
                            <label for="editEmail" class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Email</label>
                            <input id="editEmail" name="email" type="email" class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" />
                        </div>

                        <div>
                            <label for="editPhone" class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Phone</label>
                            <input id="editPhone" name="phone" type="text" class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" />
                        </div>

                        <div>
                            <label for="editAddress" class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">Address</label>
                            <input id="editAddress" name="address" type="text" class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" />
                        </div>

                        <div class="sm:col-span-2 flex gap-3 justify-end mt-2">
                            <button type="button" onclick="closeEditModal()" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-white dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.05]">
                                Cancel
                            </button>
                            <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 active:bg-blue-800">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- View Details Modal --}}
        <div id="viewModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-6"
            onclick="if (event.target === this) closeViewModal()"
            role="dialog" aria-modal="true" aria-labelledby="viewModalTitle">
            <div class="w-full max-w-lg max-h-[85vh] flex flex-col overflow-hidden rounded-3xl bg-white shadow-2xl dark:bg-gray-900">

                {{-- Compact header --}}
                <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-white/10">
                    <div id="viewAvatar" class="relative flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-amber-100 font-serif text-sm font-semibold text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                        <span id="avatarInitials" class="absolute inset-0 flex items-center justify-center"></span>
                        <img src="" alt="" class="relative z-10 hidden h-full w-full rounded-full object-cover" id="avatarID">
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 id="viewName" class="truncate font-serif text-base font-semibold text-gray-900 dark:text-white"></h3>
                    </div>
                    <span id="viewStatus" class="inline-flex flex-shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold"></span>
                    <button type="button" onclick="closeViewModal()" aria-label="Close"
                        class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full text-gray-400 transition hover:bg-black/5 hover:text-gray-600 dark:hover:bg-white/10 dark:hover:text-gray-300">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Tabs --}}
                <div class="flex border-b border-gray-100 px-6 dark:border-white/10">
                    <button type="button" data-tab="verification" onclick="switchViewTab('verification')"
                        class="view-tab-btn border-b-2 border-amber-500 px-3 py-3 text-sm font-medium text-gray-800 dark:text-white">
                        Verification
                    </button>
                    <button type="button" data-tab="bookings" onclick="switchViewTab('bookings')"
                        class="view-tab-btn border-b-2 border-transparent px-3 py-3 text-sm font-medium text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        Bookings <span id="viewBookingsCount" class="ml-1 text-xs text-gray-400 dark:text-gray-500"></span>
                    </button>
                </div>

                {{-- Tab content --}}
                <div class="flex-1 overflow-y-auto px-6 py-5">

                    {{-- Verification tab --}}
                    <div id="tab-verification" class="view-tab-panel space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Valid ID</p>
                            <span id="viewIdStatus" class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold"></span>
                        </div>

                        <img id="viewValidID" src="" alt="Valid ID" class="hidden w-full max-h-72 object-contain rounded-md border dark:border-gray-700" />
                        <p id="viewValidIDEmpty" class="hidden text-sm text-gray-400 dark:text-gray-500">No valid ID uploaded.</p>

                        <div id="verifyIdActions" class="flex gap-2 pt-1">
                            <form id="verifyIdForm" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="verified">
                                <button type="submit"
                                    class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-emerald-700 active:bg-emerald-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12l5 5L20 7" />
                                    </svg>
                                    Verify
                                </button>
                            </form>
                            <form id="rejectIdForm" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit"
                                    class="inline-flex items-center gap-1 rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-red-700 active:bg-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Bookings tab --}}
                    <div id="tab-bookings" class="view-tab-panel hidden space-y-2">
                        <div id="viewBookingsList" class="space-y-2"></div>
                        <p id="viewBookingsEmpty" class="hidden text-sm text-gray-400 dark:text-gray-500">No bookings yet.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        const modal = document.getElementById('statusModal');
        const editModal = document.getElementById('editModal');
        const viewModal = document.getElementById('viewModal');
        const STORAGE_URL = "{{ asset('storage') }}";

        const routes = {
            activate: '{{ route('guest-management.activate', ['id' => '__ID__']) }}',
            deactivate: '{{ route('guest-management.deactivate', ['id' => '__ID__']) }}',
        };

        const editRoute = '{{ route('guest-management.update', ['id' => '__ID__']) }}';
        const verifyRoute = '{{ route('guest-management.verify-id', ['id' => '__ID__']) }}';

        // Colors keyed by booking status. Add/rename keys to match your enum values.
        const bookingStatusStyles = {
            confirmed: 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
            booked: 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
            pending: 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            completed: 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
            cancelled: 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        };

        function openStatusModal(guestId, action) {
            const isDeactivate = action === 'deactivate';

            document.getElementById('statusForm').action = routes[action].replace('__ID__', guestId);
            document.getElementById('modalIcon').className = `mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full ${isDeactivate ? 'bg-red-100 dark:bg-red-900/30' : 'bg-green-100 dark:bg-green-900/30'}`;
            document.getElementById('modalIcon').innerHTML = isDeactivate
                ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 12l5 5L20 7"/></svg>`;
            document.getElementById('modalTitle').textContent = isDeactivate ? 'Deactivate guest?' : 'Activate guest?';
            document.getElementById('modalMessage').textContent = isDeactivate
                ? 'This guest will lose access to the system. You can reactivate them at any time.'
                : 'This guest will regain access to the system.';
            document.getElementById('modalConfirmBtn').className = `w-full rounded-lg px-4 py-2 text-sm font-medium text-white transition ${isDeactivate ? 'bg-red-600 hover:bg-red-700 active:bg-red-800' : 'bg-green-600 hover:bg-green-700 active:bg-green-800'}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeStatusModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openEditModal(guestId, name, email, phone, address) {
            document.getElementById('editForm').action = editRoute.replace('__ID__', guestId);
            document.getElementById('editName').value = name || '';
            document.getElementById('editEmail').value = email || '';
            document.getElementById('editPhone').value = phone || '';
            document.getElementById('editAddress').value = address || '';
            const editAvatar = document.getElementById('editAvatar');
            if (editAvatar) {
                const initials = (name || '').split(' ').map(n => n[0] || '').slice(0,2).join('').toUpperCase();
                editAvatar.textContent = initials;
            }

            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        }


        function openViewModal(guest) {
            guest = guest || {};

            document.getElementById('viewName').textContent = guest.name || '';

            const initials = (guest.name || '').split(' ').map(n => n[0] || '').slice(0, 2).join('').toUpperCase();
            document.getElementById('avatarInitials').textContent = initials;

            const avatarImg = document.getElementById('avatarID');
            if (guest.avatar) {
                avatarImg.src = STORAGE_URL + '/' + guest.avatar;
                avatarImg.alt = 'Avatar';
                avatarImg.classList.remove('hidden');
            } else {
                avatarImg.src = '';
                avatarImg.alt = '';
                avatarImg.classList.add('hidden');
            }

            const statusEl = document.getElementById('viewStatus');
            const isActive = guest.status === 'active';
            statusEl.textContent = isActive ? 'Active' : 'Inactive';
            statusEl.className = 'inline-flex flex-shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold ' +
                (isActive
                    ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                    : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400');

            const validImg = document.getElementById('viewValidID');
            const validImgEmpty = document.getElementById('viewValidIDEmpty');
            const verifyActions = document.getElementById('verifyIdActions');
            if (guest.valid_id) {
                validImg.src = STORAGE_URL + '/' + guest.valid_id;
                validImg.alt = 'Valid ID';
                validImg.classList.remove('hidden');
                validImgEmpty.classList.add('hidden');
                verifyActions.classList.toggle('hidden', guest.valid_id_status === 'verified');
            } else {
                validImg.src = '';
                validImg.alt = '';
                validImg.classList.add('hidden');
                validImgEmpty.classList.remove('hidden');
                verifyActions.classList.add('hidden');
            }

            const idStatusEl = document.getElementById('viewIdStatus');
            const idStatusStyles = {
                verified: 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                pending:  'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                rejected: 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            };
            const idStatus = guest.valid_id_status in idStatusStyles ? guest.valid_id_status : 'pending';
            idStatusEl.textContent = idStatus.charAt(0).toUpperCase() + idStatus.slice(1);
            idStatusEl.className = 'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ' + idStatusStyles[idStatus];

            document.getElementById('verifyIdForm').action = verifyRoute.replace('__ID__', guest.id);
            document.getElementById('rejectIdForm').action = verifyRoute.replace('__ID__', guest.id);

            renderBookingHistory(guest.bookings || []);

            switchViewTab('verification');

            document.getElementById('viewModal').classList.remove('hidden');
            document.getElementById('viewModal').classList.add('flex');
        }

        function switchViewTab(tab) {
            document.querySelectorAll('.view-tab-panel').forEach(el => el.classList.add('hidden'));
            document.getElementById(`tab-${tab}`).classList.remove('hidden');

            document.querySelectorAll('.view-tab-btn').forEach(btn => {
                const active = btn.dataset.tab === tab;
                btn.classList.toggle('border-amber-500', active);
                btn.classList.toggle('text-gray-800', active);
                btn.classList.toggle('dark:text-white', active);
                btn.classList.toggle('border-transparent', !active);
                btn.classList.toggle('text-gray-400', !active);
            });
        }

        function renderBookingHistory(bookings) {
            const list = document.getElementById('viewBookingsList');
            const empty = document.getElementById('viewBookingsEmpty');
            const count = document.getElementById('viewBookingsCount');

            list.innerHTML = '';
            count.textContent = bookings.length ? `(${bookings.length})` : '';

            if (!bookings.length) {
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');

            bookings.forEach(booking => {
                const statusKey = (booking.status || '').toLowerCase();
                const statusClass = bookingStatusStyles[statusKey]
                    || 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400';
                const statusLabel = booking.status
                    ? booking.status.charAt(0).toUpperCase() + booking.status.slice(1)
                    : 'Unknown';
                const amount = booking.total_amount != null
                    ? Number(booking.total_amount).toLocaleString(undefined, { style: 'currency', currency: 'PHP' })
                    : null;

                const row = document.createElement('div');
                row.className = 'flex items-center justify-between gap-3 rounded-xl border border-gray-100 px-3 py-2.5 dark:border-white/10';
                row.innerHTML = `
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-gray-800 dark:text-gray-100">${booking.room || 'Room N/A'} &middot; ${booking.reference || ''}</p>
                        <p class="truncate text-xs text-gray-400 dark:text-gray-500">${booking.check_in || '—'} → ${booking.check_out || '—'}</p>
                    </div>
                    <div class="flex flex-shrink-0 items-center gap-2">
                        ${amount ? `<span class="text-xs font-medium text-gray-500 dark:text-gray-400">${amount}</span>` : ''}
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold ${statusClass}">${statusLabel}</span>
                    </div>
                `;
                list.appendChild(row);
            });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.getElementById('viewModal').classList.remove('flex');
        }
    </script>

    <script>
        setTimeout(() => {
            ['flash-success', 'flash-error'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                }
            });
        }, 4000);
    </script>

    <script>
        function filterTable() {
            const search = document.getElementById("search-input").value.toLowerCase();

            document.querySelectorAll(".guest-row").forEach(card => {
                const text = card.dataset.search.toLowerCase();

                card.style.display = text.includes(search) ? "" : "none";
            });
        }
    </script>
@endpush

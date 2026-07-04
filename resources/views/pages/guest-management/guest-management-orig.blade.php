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
        </div>

        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">

                <thead>
                    <tr class="border-y border-gray-100 dark:border-gray-800">
                        <th class="py-3 pr-4 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Name</th>
                        <th class="py-3 pr-4 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Email</th>
                        <!-- <th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Roles</th> -->
                        <th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Phone</th>
                        <th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Address</th>
                        {{-- Actions --}}
                        <th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($guests as $guest)
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-3">
                                    @if($guest['avatar'])
                                        <img src="{{ asset('storage/' . $guest['avatar']) }}" alt="{{ $guest['name'] }}'s Avatar" class="h-9 w-9 rounded-full object-cover">
                                    @else
                                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 text-sm font-medium text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                            {{ strtoupper(substr($guest['name'], 0, 2)) }}
                                        </div>
                                    @endif
                                    <span class="font-medium text-gray-800 dark:text-white/90">
                                        {{ $guest['name'] }}
                                    </span>
                                </div>
                            </td>

                            <td class="py-3 pr-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $guest['email'] }}
                            </td>

                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $guest['phone'] ?? 'N/A' }}
                            </td>

                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $guest['address'] ?? 'N/A' }}
                            </td>

                            <td class="py-3 text-sm">
                                 @if($guest['roles'] && in_array(['admin', 'staff'], $guest['roles']))
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-gray-500 px-3 py-1 text-xs font-medium text-white cursor-not-allowed">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
                                        </svg>
                                        Protected
                                    </span>
                                @else
                                    <div class="inline-flex items-center gap-2 flex-wrap">

                                        <button type="button"
                                                onclick='openViewModal({{ json_encode($guest["id"]) }}, {{ json_encode($guest["avatar"]) }}, {{ json_encode($guest["name"] ?? "") }}, {{ json_encode($guest["email"] ?? "") }}, {{ json_encode($guest["phone"] ?? "") }}, {{ json_encode($guest["address"] ?? "") }}, {{ json_encode($guest["status"] ?? "") }}, {{ json_encode($guest["valid_id"] ?? "") }})'
                                                class="inline-flex items-center gap-1 rounded-lg bg-slate-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-slate-700 active:bg-slate-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path d="M2.05 12a9.95 9.95 0 0119.9 0 9.95 9.95 0 01-19.9 0z" />
                                            </svg>
                                            View
                                        </button>

                                        {{-- <button type="button"
                                                onclick='openEditModal({{ json_encode($guest["id"]) }}, {{ json_encode($guest["name"] ?? "") }}, {{ json_encode($guest["email"] ?? "") }}, {{ json_encode($guest["phone"] ?? "") }}, {{ json_encode($guest["address"] ?? "") }})'
                                                class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-blue-700 active:bg-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9" /><path d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                            Edit
                                        </button> --}}

                                        {{-- @if ($guest['status'] === 'active')
                                            <button type="button"
                                                    onclick="openStatusModal('{{ $guest['id'] }}', 'deactivate')"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-red-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-red-700 active:bg-red-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
                                                </svg>
                                                Deactivate
                                            </button>

                                            <form action="{{ route('guest-management.reset-password', $guest['id']) }}" method="POST" class="inline-flex">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 rounded-lg bg-yellow-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-yellow-700 active:bg-yellow-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 20h9" />
                                                        <path d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
                                                    </svg>
                                                    Reset Password
                                                </button>
                                            </form>
                                        @else
                                            <button type="button"
                                                    onclick="openStatusModal('{{ $guest['id'] }}', 'activate')"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-green-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-green-700 active:bg-green-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M5 12l5 5L20 7" />
                                                </svg>
                                                Activate
                                            </button>
                                        @endif --}}
                                    </div>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

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
                    <form id="editForm" method="POST" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
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
            <div class="w-full max-w-2xl overflow-hidden rounded-3xl bg-white shadow-2xl dark:bg-gray-900">

                {{-- Header --}}
                <div class="relative bg-gradient-to-br from-amber-50 to-white px-6 pt-6 pb-5 dark:from-gray-800 dark:to-gray-900">
                    <button type="button" onclick="closeViewModal()" aria-label="Close"
                        class="absolute right-5 top-5 flex h-8 w-8 items-center justify-center rounded-full text-gray-400 transition hover:bg-black/5 hover:text-gray-600 dark:hover:bg-white/10 dark:hover:text-gray-300">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-4">
                        <div id="viewAvatar" class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-100 font-serif text-xl font-semibold text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                            <img src="" alt="" class="h-full w-full rounded-full object-cover" id="avatarID">
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-amber-600 dark:text-amber-400">Guest Details</p>
                            <h3 id="viewName" class="font-serif text-2xl font-semibold text-gray-900 dark:text-white"></h3>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-50 dark:bg-white/5">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Email</p>
                                <p id="viewEmail" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100"></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-50 dark:bg-white/5">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Phone</p>
                                <p id="viewPhone" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100"></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 sm:col-span-2">
                            <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-50 dark:bg-white/5">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Address</p>
                                <p id="viewAddress" class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100"></p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-50 dark:bg-white/5">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Status</p>
                                <span id="viewStatus" class="mt-1 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold"></span>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Valid ID</p>
                            <img id="viewValidID" src="" alt="Valid ID" class="hidden w-full max-h-60 object-contain rounded-md border" />
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 border-t border-gray-100 bg-gray-50/60 px-6 py-4 dark:border-white/10 dark:bg-white/[0.02]">
                    <button type="button" onclick="closeViewModal()"
                        class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-white dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.05]">
                        Close
                    </button>
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
            // populate avatar initials for header
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

        function openViewModal(id, avatar, name, email, phone, address, status, valid_id) {
            // normalize inputs (roles may be passed as array or string)
            const guestName = name || '';
            const guest = {
                id: id,
                name: guestName,
                avatar: avatar || '',
                email: email || '',
                phone: phone || '',
                address: address || '',
                status: status || '',
                valid_id: valid_id || '', // Placeholder for valid ID, adjust as needed
            };

            document.getElementById('viewName').textContent = guest.name;
            document.getElementById('viewEmail').textContent = guest.email;
            document.getElementById('viewPhone').textContent = guest.phone;
            document.getElementById('viewAddress').textContent = guest.address;

            // Avatar initials
            const initials = (guest.name || '').split(' ').map(n => n[0] || '').slice(0, 2).join('').toUpperCase();
            document.getElementById('avatarID').textContent = initials;

            // Status badge
            const statusEl = document.getElementById('viewStatus');
            const isActive = guest.status === 'active';
            statusEl.textContent = isActive ? 'Active' : 'Inactive';
            statusEl.className = 'mt-1 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ' +
                (isActive
                    ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                    : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400');

            // Valid ID image
            const validImg = document.getElementById('viewValidID');
            if (guest.valid_id) {
                validImg.src = STORAGE_URL + '/' + guest.valid_id;
                validImg.alt = 'Valid ID';
                validImg.classList.remove('hidden');
            } else {
                validImg.src = '';
                validImg.alt = '';
                validImg.classList.add('hidden');
            }

            // Avatar image
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

            document.getElementById('viewModal').classList.remove('hidden');
            document.getElementById('viewModal').classList.add('flex');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.getElementById('viewModal').classList.remove('flex');
        }

        // modal.addEventListener('click', function (e) {
        //     if (e.target === modal) closeStatusModal();
        // });

        // editModal.addEventListener('click', function (e) {
        //     if (e.target === editModal) closeEditModal();
        // });

        // viewModal.addEventListener('click', function (e) {
        //     if (e.target === viewModal) closeViewModal();
        // });

        // document.addEventListener('keydown', function (e) {
        //     if (e.key === 'Escape') {
        //         closeStatusModal();
        //         closeEditModal();
        //         closeViewModal();
        //     }
        // });
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
@endpush

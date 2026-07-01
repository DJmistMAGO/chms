@extends('layouts.authenticated.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="User Management" />

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
                Users
            </h3>

            <button type="button"  onclick="document.getElementById('addStaffModal').classList.remove('hidden'); document.getElementById('addStaffModal').classList.add('flex');
            " class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-amber-50 transition hover:bg-amber-700 active:bg-amber-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z" fill="none"/> <path d="M12 5l0 14" /> <path d="M5 12l14 0" />
                </svg>
                Add Staff User
            </button>
        </div>

        {{-- Add Staff Modal --}}
        <div id="addStaffModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white"> Add Staff User</h2>
                    <button type="button" onclick="
                    document.getElementById('addStaffModal').classList.add('hidden');
                    document.getElementById('addStaffModal').classList.remove('flex');
                    "  class="text-gray-500 hover:text-gray-700 dark:hover:text-white" >
                        ✕
                    </button>
                </div>

                <form action="{{ route('user-management.addStaff') }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> Name  </label>
                            <input type="text" name="name" required class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> Email </label>
                            <input type="email"  name="email" required class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white" >
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> Password </label>
                            <input type="password" name="password"  required class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white" >
                        </div>

                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button  type="button" onclick="document.getElementById('addStaffModal').classList.add('hidden'); document.getElementById('addStaffModal').classList.remove('flex');" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                            Cancel
                        </button>

                        <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700">
                            Create User
                        </button>

                    </div>
                </form>
            </div>
        </div>

        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">

                <thead>
                    <tr class="border-y border-gray-100 dark:border-gray-800">
                        <th class="py-3 pr-4 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Name</th>
                        <th class="py-3 pr-4 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Email</th>
                        <th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Roles</th>
                        <th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Phone</th>
                        <th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Address</th>
                        {{-- Actions --}}
                        <th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach ($users as $user)
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-3">
                                    @if($user['avatar'])
                                        <img src="{{ asset('storage/' . $user['avatar']) }}" alt="{{ $user['name'] }}'s Avatar" class="h-9 w-9 rounded-full object-cover">
                                    @else
                                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 text-sm font-medium text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                            {{ strtoupper(substr($user['name'], 0, 2)) }}
                                        </div>
                                    @endif
                                    <span class="font-medium text-gray-800 dark:text-white/90">
                                        {{ $user['name'] }}
                                    </span>
                                </div>
                            </td>

                            <td class="py-3 pr-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $user['email'] }}
                            </td>

                            <td class="py-3">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($user['roles'] as $role)
                                        @php
                                            $badgeClass = match($role) {
                                                'admin'     => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                                                'staff'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                                'client'      => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClass }}">
                                            {{ ucfirst($role) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $user['phone'] ?? 'N/A' }}
                            </td>

                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">
                                {{ $user['address'] ?? 'N/A' }}
                            </td>

                            <td class="py-3 text-sm">
                                @if($user['roles'] && in_array('admin', $user['roles']))
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
                                                onclick='openEditModal({{ json_encode($user["id"]) }}, {{ json_encode($user["name"] ?? "") }}, {{ json_encode($user["email"] ?? "") }}, {{ json_encode($user["phone"] ?? "") }}, {{ json_encode($user["address"] ?? "") }})'
                                                class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-blue-700 active:bg-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 20h9" /><path d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
                                            </svg>
                                            Edit
                                        </button>

                                        @if ($user['status'] === 'active')
                                            <button type="button"
                                                    onclick="openStatusModal('{{ $user['id'] }}', 'deactivate')"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-red-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-red-700 active:bg-red-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
                                                </svg>
                                                Deactivate
                                            </button>

                                            <form action="{{ route('user-management.reset-password', $user['id']) }}" method="POST" class="inline-flex">
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
                                                    onclick="openStatusModal('{{ $user['id'] }}', 'activate')"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-green-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-green-700 active:bg-green-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M5 12l5 5L20 7" />
                                                </svg>
                                                Activate
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Status Modal --}}
        <div id="statusModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">

                <div id="modalIcon" class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full">
                    {{-- Icon swapped via JS --}}
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

        {{-- Edit Modal --}}
        <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
            <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
                <h3 class="mb-4 text-center text-base font-semibold text-gray-800 dark:text-white/90">Edit User</h3>
                <form id="editForm" method="POST" class="space-y-4">
                    @csrf
                    {{-- @method('PUT') --}}

                    <div>
                        <label for="editName" class="text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input id="editName" name="name" type="text" class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" />
                    </div>

                    <div>
                        <label for="editEmail" class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input id="editEmail" name="email" type="email" class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" />
                    </div>

                    <div>
                        <label for="editPhone" class="text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                        <input id="editPhone" name="phone" type="text" class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" />
                    </div>

                    <div>
                        <label for="editAddress" class="text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                        <input id="editAddress" name="address" type="text" class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" />
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeEditModal()" class="flex-1 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 active:bg-blue-800">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        const modal = document.getElementById('statusModal');
        const editModal = document.getElementById('editModal');

        const routes = {
            activate: '{{ route('user-management.activate', ['id' => '__ID__']) }}',
            deactivate: '{{ route('user-management.deactivate', ['id' => '__ID__']) }}',
        };

        const editRoute = '{{ route('user-management.update', ['id' => '__ID__']) }}';

        function openStatusModal(userId, action) {
            const isDeactivate = action === 'deactivate';

            document.getElementById('statusForm').action = routes[action].replace('__ID__', userId);
            document.getElementById('modalIcon').className = `mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full ${isDeactivate ? 'bg-red-100 dark:bg-red-900/30' : 'bg-green-100 dark:bg-green-900/30'}`;
            document.getElementById('modalIcon').innerHTML = isDeactivate
                ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 12l5 5L20 7"/></svg>`;
            document.getElementById('modalTitle').textContent = isDeactivate ? 'Deactivate User?' : 'Activate User?';
            document.getElementById('modalMessage').textContent = isDeactivate
                ? 'This user will lose access to the system. You can reactivate them at any time.'
                : 'This user will regain access to the system.';
            document.getElementById('modalConfirmBtn').className = `w-full rounded-lg px-4 py-2 text-sm font-medium text-white transition ${isDeactivate ? 'bg-red-600 hover:bg-red-700 active:bg-red-800' : 'bg-green-600 hover:bg-green-700 active:bg-green-800'}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeStatusModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openEditModal(userId, name, email, roles, phone, address) {
            document.getElementById('editForm').action = editRoute.replace('__ID__', userId);
            document.getElementById('editName').value = name || '';
            document.getElementById('editEmail').value = email || '';
            document.getElementById('editPhone').value = phone || '';
            document.getElementById('editAddress').value = address || '';
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        }

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeStatusModal();
        });

        editModal.addEventListener('click', function (e) {
            if (e.target === editModal) closeEditModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeStatusModal();
                closeEditModal();
            }
        });
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

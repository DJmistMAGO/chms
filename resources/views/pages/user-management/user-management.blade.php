@extends('layouts.authenticated.app')

@section('content')
	<x-common.toast-notification />

	<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">

		<div class="mb-5 flex items-center justify-between lg:mb-7">
			<h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
				User Management
			</h3>

			<button type="button" onclick="openAddStaffModal()"
				class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-amber-50 transition hover:bg-amber-700 active:bg-amber-800">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
					stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M12 5v14" />
					<path d="M5 12h14" />
				</svg>
				Add Staff User
			</button>
		</div>

		<div class="mb-5 border-b border-gray-200 dark:border-gray-800">
			<nav class="-mb-px flex gap-6" aria-label="User types">
				<button type="button" id="staffTab" onclick="switchUserTab('staff')"
					class="user-tab border-b-2 border-amber-600 px-1 pb-3 text-sm font-medium text-amber-600 transition">
					Staff
					<span
						class="ml-1.5 rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
						{{ $staffUsers->count() }}
					</span>
				</button>

				<button type="button" id="clientTab" onclick="switchUserTab('client')"
					class="user-tab border-b-2 border-transparent px-1 pb-3 text-sm font-medium text-gray-500 transition hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
					Clients
					<span
						class="ml-1.5 rounded-full bg-green-100 px-2 py-0.5 text-xs text-green-700 dark:bg-green-900/40 dark:text-green-300">
						{{ $clientUsers->count() }}
					</span>
				</button>
			</nav>
		</div>

		<div class="mb-5 relative">
			<svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none"
				stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z" />
			</svg>
			<input type="text" id="search-input" placeholder="Search by name, email, phone number, or address"
				oninput="filterTable()"
				class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-700 transition focus:border-yellow-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white">
		</div>

		<div id="addStaffModal"
			class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 backdrop-blur-sm">
			<div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
				<div class="mb-5 flex items-center justify-between">
					<h2 class="text-xl font-semibold text-gray-800 dark:text-white">
						Add Staff User
					</h2>
				</div>

				<form action="{{ route('user-management.addStaff') }}" method="POST" autocomplete="off">
					@csrf
					<div class="space-y-4">
						<div>
							<label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> Name </label>
							<input type="text" name="name" required
								class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
						</div>
						<div>
							<label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> Email </label>
							<input type="email" name="email" required autocomplete="off"
								class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
						</div>

						<div>
							<label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"> Password </label>
							<input type="password" name="password" required autocomplete="new-password"
								class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
						</div>
					</div>

					<div class="mt-6 flex justify-end gap-3">
						<button type="button" onclick="closeAddStaffModal()"
							class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
							Cancel
						</button>

						<button type="submit"
							class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-700">
							Create User
						</button>
					</div>
				</form>
			</div>
		</div>

		<div id="staffTable" class="user-table">
			<div class="max-w-full overflow-x-auto custom-scrollbar">
				<table class="min-w-full">
					<thead>
						<tr class="border-y border-gray-100 dark:border-gray-800">
							<th class="py-3 pr-4 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400"> Name
							</th>
							<th class="py-3 pr-4 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
								Email </th>
							<th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400"> Phone
							</th>
							<th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400"> Address
							</th>
							<th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400"> Status
							</th>
							<th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400"> Actions
							</th>
						</tr>
					</thead>

					<tbody class="divide-y divide-gray-100 dark:divide-gray-800">
						@forelse ($staffUsers as $user)
							<tr class="user-row transition hover:bg-gray-50 dark:hover:bg-white/[0.02]"
								data-search="{{ strtolower($user['name']) }} {{ strtolower($user['email']) }} {{ strtolower($user['phone'] ?? '') }} {{ strtolower($user['address'] ?? '') }}">
								<td class="py-3 pr-4">
									<div class="flex items-center gap-3">
										@if ($user['avatar'])
											<img src="{{ asset('storage/' . $user['avatar']) }}" alt="{{ $user['name'] }}'s Avatar"
												class="h-9 w-9 rounded-full object-cover">
										@else
											<div
												class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 text-sm font-medium text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
												{{ strtoupper(substr($user['name'], 0, 2)) }}
											</div>
										@endif

										<span class="font-medium text-gray-800 dark:text-white/90"> {{ $user['name'] }} </span>
									</div>
								</td>

								<td class="py-3 pr-4 text-sm text-gray-500 dark:text-gray-400"> {{ $user['email'] }} </td>
								<td class="py-3 text-sm text-gray-500 dark:text-gray-400"> {{ $user['phone'] ?? 'N/A' }} </td>
								<td class="py-3 text-sm text-gray-500 dark:text-gray-400"> {{ $user['address'] ?? 'N/A' }} </td>
								<td class="py-3">
									@if ($user['status'] === 'active')
										<span
											class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">
											Active
										</span>
									@else
										<span
											class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900/40 dark:text-red-300">
											Inactive
										</span>
									@endif
								</td>

								<td class="py-3 text-sm">
									<div class="inline-flex flex-wrap items-center gap-2">
										<button type="button"
											onclick='openEditModal(
                                                {{ json_encode($user['id']) }},
                                                {{ json_encode($user['name'] ?? '') }},
                                                {{ json_encode($user['email'] ?? '') }},
                                                {{ json_encode($user['phone'] ?? '') }},
                                                {{ json_encode($user['address'] ?? '') }}
                                            )'
											class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-blue-700 active:bg-blue-800">
											<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2">
												<path d="M12 20h9" />
												<path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
											</svg>
											Edit
										</button>

										@if ($user['status'] === 'active')
											<button type="button" onclick="openStatusModal('{{ $user['id'] }}', 'deactivate')"
												class="inline-flex items-center gap-1 rounded-lg bg-red-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-red-700 active:bg-red-800">
												Deactivate
											</button>
										@else
											<button type="button" onclick="openStatusModal('{{ $user['id'] }}', 'activate')"
												class="inline-flex items-center gap-1 rounded-lg bg-green-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-green-700 active:bg-green-800">
												Activate
											</button>
										@endif
										<form action="{{ route('user-management.reset-password', $user['id']) }}" method="POST"
											class="inline-flex">
											@csrf
											<button type="submit"
												class="inline-flex items-center gap-1 rounded-lg bg-yellow-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-yellow-700 active:bg-yellow-800">
												Reset Password
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="py-10 text-center text-sm text-gray-500 dark:text-gray-400">
									No staff users found.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<div id="clientTable" class="user-table hidden">
			<div class="max-w-full overflow-x-auto custom-scrollbar">
				<table class="min-w-full">
					<thead>
						<tr class="border-y border-gray-100 dark:border-gray-800">
							<th class="py-3 pr-4 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
								Name </th>
							<th class="py-3 pr-4 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
								Email </th>
							<th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400"> Phone
							</th>
							<th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400"> Address
							</th>
							<th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400"> Status
							</th>
							<th class="py-3 text-left text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400"> Actions
							</th>
						</tr>
					</thead>

					<tbody class="divide-y divide-gray-100 dark:divide-gray-800">
						@forelse ($clientUsers as $user)
							<tr class="user-row transition hover:bg-gray-50 dark:hover:bg-white/[0.02]"
								data-search="{{ strtolower($user['name']) }} {{ strtolower($user['email']) }} {{ strtolower($user['phone'] ?? '') }} {{ strtolower($user['address'] ?? '') }}">
								<td class="py-3 pr-4">
									<div class="flex items-center gap-3">
										@if ($user['avatar'])
											<img src="{{ asset('storage/' . $user['avatar']) }}" alt="{{ $user['name'] }}'s Avatar"
												class="h-9 w-9 rounded-full object-cover">
										@else
											<div
												class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-green-100 text-sm font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">
												{{ strtoupper(substr($user['name'], 0, 2)) }}
											</div>
										@endif

										<span class="font-medium text-gray-800 dark:text-white/90"> {{ $user['name'] }} </span>
									</div>
								</td>

								<td class="py-3 pr-4 text-sm text-gray-500 dark:text-gray-400"> {{ $user['email'] }} </td>
								<td class="py-3 text-sm text-gray-500 dark:text-gray-400"> {{ $user['phone'] ?? 'N/A' }} </td>
								<td class="py-3 text-sm text-gray-500 dark:text-gray-400"> {{ $user['address'] ?? 'N/A' }} </td>
								<td class="py-3">
									@if ($user['status'] === 'active')
										<span
											class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/40 dark:text-green-300">
											Active
										</span>
									@else
										<span
											class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900/40 dark:text-red-300">
											Inactive
										</span>
									@endif
								</td>

								<td class="py-3 text-sm">
									<div class="inline-flex flex-wrap items-center gap-2">
										<button type="button"
											onclick='openEditModal(
                                                {{ json_encode($user['id']) }},
                                                {{ json_encode($user['name'] ?? '') }},
                                                {{ json_encode($user['email'] ?? '') }},
                                                {{ json_encode($user['phone'] ?? '') }},
                                                {{ json_encode($user['address'] ?? '') }}
                                            )'
											class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-blue-700 active:bg-blue-800">
											<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
												stroke="currentColor" stroke-width="2">
												<path d="M12 20h9" />
												<path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
											</svg>
											Edit
										</button>

										@if ($user['status'] === 'active')
											<button type="button" onclick="openStatusModal('{{ $user['id'] }}', 'deactivate')"
												class="inline-flex items-center gap-1 rounded-lg bg-red-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-red-700 active:bg-red-800">
												Deactivate
											</button>
										@else
											<button type="button" onclick="openStatusModal('{{ $user['id'] }}', 'activate')"
												class="inline-flex items-center gap-1 rounded-lg bg-green-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-green-700 active:bg-green-800">
												Activate
											</button>
										@endif

										<form action="{{ route('user-management.reset-password', $user['id']) }}" method="POST"
											class="inline-flex">
											@csrf
											<button type="submit"
												class="inline-flex items-center gap-1 rounded-lg bg-yellow-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-yellow-700 active:bg-yellow-800">
												Reset Password
											</button>
										</form>
									</div>
								</td>
							</tr>

						@empty
							<tr>
								<td colspan="6" class="py-10 text-center text-sm text-gray-500 dark:text-gray-400">
									No client users found.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>

		<div id="statusModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
			<div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
				<div id="modalIcon" class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full"> </div>
				<h3 id="modalTitle" class="mb-2 text-center text-base font-semibold text-gray-800 dark:text-white/90"></h3>
				<p id="modalMessage" class="mb-6 text-center text-sm text-gray-500 dark:text-gray-400"></p>

				<div class="flex gap-3">
					<button type="button" onclick="closeStatusModal()"
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

		<div id="editModal"
			class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6 backdrop-blur-sm" role="dialog"
			aria-modal="true" aria-labelledby="editModalTitle">
			<div class="w-full max-w-2xl overflow-hidden rounded-3xl bg-white shadow-2xl dark:bg-gray-900">
				<div class="relative bg-gradient-to-br from-amber-50 to-white px-6 pb-5 pt-6 dark:from-gray-800 dark:to-gray-900">
					<button type="button" onclick="closeEditModal()" aria-label="Close"
						class="absolute right-5 top-5 flex h-8 w-8 items-center justify-center rounded-full text-gray-400 transition hover:bg-black/5 hover:text-gray-600 dark:hover:bg-white/10 dark:hover:text-gray-300">
						<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>

					<div class="flex items-center gap-4">
						<div id="editAvatar"
							class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-100 font-serif text-xl font-semibold text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
						</div>
						<div>
							<p class="text-xs font-semibold uppercase tracking-widest text-amber-600 dark:text-amber-400"> Edit User </p>
							<h3 id="editModalTitle" class="font-serif text-2xl font-semibold text-gray-900 dark:text-white">
								Edit user
							</h3>
						</div>
					</div>
				</div>

				<div class="px-6 py-6">
					<form id="editForm" method="POST" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
						@csrf
						<div>
							<label for="editName" class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
								Name </label>
							<input id="editName" name="name" type="text" required
								class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
						</div>

						<div>
							<label for="editEmail" class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
								Email </label>
							<input id="editEmail" name="email" type="email" required
								class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
						</div>

						<div>
							<label for="editPhone" class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
								Phone </label>
							<input id="editPhone" name="phone" type="text"
								class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
						</div>

						<div>
							<label for="editAddress"
								class="text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500"> Address </label>
							<input id="editAddress" name="address" type="text"
								class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
						</div>

						<div class="mt-2 flex justify-end gap-3 sm:col-span-2">
							<button type="button" onclick="closeEditModal()"
								class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.05]">
								Cancel
							</button>
							<button type="submit"
								class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 active:bg-blue-800">
								Save Changes
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		const statusModal = document.getElementById('statusModal');
		const editModal = document.getElementById('editModal');
		const addStaffModal = document.getElementById('addStaffModal');

		const routes = {
			activate: '{{ route('user-management.activate', ['id' => '__ID__']) }}',
			deactivate: '{{ route('user-management.deactivate', ['id' => '__ID__']) }}',
		};

		const editRoute =
			'{{ route('user-management.update', ['id' => '__ID__']) }}';

		function openAddStaffModal() {
			addStaffModal.classList.remove('hidden');
			addStaffModal.classList.add('flex');
		}


		function closeAddStaffModal() {
			addStaffModal.classList.add('hidden');
			addStaffModal.classList.remove('flex');
		}



		function openStatusModal(userId, action) {
			const isDeactivate = action === 'deactivate';
			document.getElementById('statusForm').action = routes[action].replace('__ID__', userId);

			document.getElementById('modalIcon').className =
				`mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full ${ isDeactivate ? 'bg-red-100 dark:bg-red-900/30' : 'bg-green-100 dark:bg-green-900/30' }`;

			document.getElementById('modalIcon').innerHTML = isDeactivate ?
				`
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" >
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            `

				:
				`
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" >
                    <path d="M5 12l5 5L20 7"/>
                </svg>
            `;


			document.getElementById('modalTitle').textContent =
				isDeactivate ?
				'Deactivate User?' :
				'Activate User?';


			document.getElementById('modalMessage').textContent =
				isDeactivate ?
				'This user will lose access to the system. You can reactivate them at any time.' :
				'This user will regain access to the system.';


			document.getElementById('modalConfirmBtn').className =
				`w-full rounded-lg px-4 py-2 text-sm font-medium text-white transition ${
                isDeactivate
                    ? 'bg-red-600 hover:bg-red-700 active:bg-red-800'
                    : 'bg-green-600 hover:bg-green-700 active:bg-green-800'
            }`;


			statusModal.classList.remove('hidden');
			statusModal.classList.add('flex');

		}


		function closeStatusModal() {
			statusModal.classList.add('hidden');
			statusModal.classList.remove('flex');
		}


		function openEditModal(userId, name, email, phone, address) {
			document.getElementById('editForm').action =
				editRoute.replace('__ID__', userId);


			document.getElementById('editName').value =
				name || '';

			document.getElementById('editEmail').value =
				email || '';

			document.getElementById('editPhone').value =
				phone || '';

			document.getElementById('editAddress').value =
				address || '';


			const editAvatar =
				document.getElementById('editAvatar');

			if (editAvatar) {

				const initials = (name || '')
					.split(' ')
					.map(n => n[0] || '')
					.slice(0, 2)
					.join('')
					.toUpperCase();

				editAvatar.textContent = initials;

			}


			editModal.classList.remove('hidden');
			editModal.classList.add('flex');
		}


		function closeEditModal() {
			editModal.classList.add('hidden');
			editModal.classList.remove('flex');
		}


		function switchUserTab(type) {
			const staffTable =
				document.getElementById('staffTable');

			const clientTable =
				document.getElementById('clientTable');

			const staffTab =
				document.getElementById('staffTab');

			const clientTab =
				document.getElementById('clientTab');


			const activeClasses = [
				'border-amber-600',
				'text-amber-600'
			];

			const inactiveClasses = [
				'border-transparent',
				'text-gray-500'
			];


			if (type === 'staff') {
				staffTable.classList.remove('hidden');
				clientTable.classList.add('hidden');

				staffTab.classList.add(...activeClasses);
				staffTab.classList.remove(...inactiveClasses);

				clientTab.classList.remove(...activeClasses);
				clientTab.classList.add(...inactiveClasses);
			} else {
				staffTable.classList.add('hidden');
				clientTable.classList.remove('hidden');

				clientTab.classList.add(...activeClasses);
				clientTab.classList.remove(...inactiveClasses);

				staffTab.classList.remove(...activeClasses);
				staffTab.classList.add(...inactiveClasses);
			}

			document.getElementById('search-input').value = '';
			filterTable();
		}


		function filterTable() {
			const q = document
				.getElementById('search-input')
				.value
				.toLowerCase()
				.trim();


			document
				.querySelectorAll('.user-table:not(.hidden) .user-row')
				.forEach(row => {

					row.style.display =
						(row.dataset.search || '').includes(q) ?
						'' :
						'none';

				});

		}


		statusModal.addEventListener('click', function(event) {
			if (event.target === statusModal) {
				closeStatusModal();
			}
		});


		editModal.addEventListener('click', function(event) {
			if (event.target === editModal) {
				closeEditModal();
			}
		});


		addStaffModal.addEventListener('click', function(event) {
			if (event.target === addStaffModal) {
				closeAddStaffModal();
			}
		});


		document.addEventListener('keydown', function(event) {
			if (event.key === 'Escape') {
				closeStatusModal();
				closeEditModal();
				closeAddStaffModal();
			}
		});

		setTimeout(() => {
			[
				'flash-success',
				'flash-error'
			].forEach(id => {

				const el =
					document.getElementById(id);
				if (el) {
					el.style.transition =
						'opacity 0.5s ease';
					el.style.opacity = '0';
					setTimeout(() => {
						el.remove();
					}, 500);
				}
			});
		}, 4000);
	</script>
@endpush

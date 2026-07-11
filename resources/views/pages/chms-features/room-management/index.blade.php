@extends('layouts.authenticated.app')

@section('title', 'Room Management')

@section('content')
    <x-common.page-breadcrumb pageTitle="Room Management" />

    <div
        id="statusModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modalTitle"
    >
        <div class="w-full max-w-sm rounded-2xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-700 dark:bg-gray-900">
            <div class="mb-5 flex items-center justify-between">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">
                    Update Room Status
                </h3>
                <button
                    onclick="closeModal()"
                    class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                    aria-label="Close"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="statusForm" method="POST" action="" data-confirm-leave>
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Room
                    </label>
                    <p id="modalRoomNo" class="text-2xl font-bold text-gray-900 dark:text-white">—</p>
                    <p id="modalRoomMeta" class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">—</p>
                </div>

                <div class="mb-6">
                    <label for="modalStatus" class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        New Status
                    </label>
                    <select
                        id="modalStatus"
                        name="status"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                    >
                        <option value="Available">Available</option>
                        <option value="Occupied">Occupied</option>
                        <option value="Maintenance">Under Maintenance</option>
                        <option value="Reserved">Reserved</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeModal()"
                        class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
        <div class="mx-auto w-full">

            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Room Management</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $rooms->count() }} rooms across {{ $rooms->pluck('floor')->unique()->count() }} floors
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4 text-xs font-medium">
                    <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Available
                    </span>
                    <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                        <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span> Occupied
                    </span>
                    <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                        <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Maintenance
                    </span>
                    <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span> Reserved
                    </span>
                </div>
            </div>

            <div class="space-y-10">

                @php
                    $floorLabels = [
                        '1' => 'First Floor',
                        '2' => 'Second Floor',
                        '3' => 'Third Floor',
                        '4' => 'Fourth Floor',
                    ];

                    $statusConfig = [
                        'Available'   => ['dot' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/30', 'border' => 'border-emerald-100 dark:border-emerald-500/20'],
                        'Occupied'    => ['dot' => 'bg-rose-500',    'badge' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/30',       'border' => 'border-rose-100 dark:border-rose-500/20'],
                        'Maintenance' => ['dot' => 'bg-amber-500',   'badge' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/30',   'border' => 'border-amber-100 dark:border-amber-500/20'],
                        'Reserved'    => ['dot' => 'bg-blue-500',    'badge' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/30',       'border' => 'border-blue-100 dark:border-blue-500/20'],
                    ];

                    $roomsByFloor = $rooms->groupBy('floor');
                @endphp

                @foreach ($roomsByFloor as $floor => $floorRooms)
                    <div>
                        <div class="mb-4 flex items-center gap-3">
                            <h4 class="text-sm font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                {{ $floorLabels[$floor] ?? "Floor $floor" }}
                            </h4>
                            <div class="h-px flex-1 bg-gray-100 dark:bg-gray-800"></div>
                            <span class="text-xs font-medium text-gray-400 dark:text-gray-600">
                                {{ $floorRooms->count() }} rooms
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6">
                            @foreach ($floorRooms as $room)
                                @php
                                    $cfg = $statusConfig[$room->status] ?? $statusConfig['Available'];
                                @endphp

                                <div
                                    class="group relative cursor-pointer rounded-2xl border bg-white p-4 shadow-sm transition-all duration-150 hover:-translate-y-0.5 hover:shadow-md dark:bg-gray-900 {{ $cfg['border'] }}"
                                    onclick="openModal('{{ $room->room_no }}', '{{ $room->room_type }}', '{{ $room->base_price }}', '{{ $room->status }}', '{{ $room->id }}')"
                                    role="button"
                                    tabindex="0"
                                    onkeydown="if(event.key==='Enter')openModal('{{ $room->room_no }}', '{{ $room->room_type }}', '{{ $room->base_price }}', '{{ $room->status }}', '{{ $room->id }}')"
                                    title="Click to update status"
                                >
                                    <span class="absolute right-3 top-3 h-2 w-2 rounded-full {{ $cfg['dot'] }}"></span>

                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $room->room_no }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500 leading-tight">
                                        {{ $room->room_type }}
                                    </p>

                                    <p class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                        ₱{{ number_format($room->base_price) }}
                                    </p>

                                    <span class="mt-3 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $cfg['badge'] }}">
                                        {{ $room->status }}
                                    </span>

                                    <div class="absolute inset-0 flex items-center justify-center rounded-2xl bg-white/0 opacity-0 transition-all duration-150 group-hover:bg-black/[0.03] group-hover:opacity-100 dark:group-hover:bg-white/[0.03]">
                                        <span class="rounded-lg bg-white px-2.5 py-1 text-xs font-semibold text-gray-600 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700">
                                            Update status
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <script>
        const modal    = document.getElementById('statusModal');
        const form     = document.getElementById('statusForm');
        const roomNo   = document.getElementById('modalRoomNo');
        const roomMeta = document.getElementById('modalRoomMeta');
        const status   = document.getElementById('modalStatus');

        function openModal(no, type, price, currentStatus, roomId) {
        roomNo.textContent   = no;
        roomMeta.textContent = type + ' · ₱' + Number(price).toLocaleString();
        status.value         = currentStatus;

        form.action = "{{ url('room-management') }}/" + roomId;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // modal.addEventListener('click', function (e) {
        //     if (e.target === modal) closeModal();
        // });

        // document.addEventListener('keydown', function (e) {
        //     if (e.key === 'Escape') closeModal();
        // });
    </script>
@endsection

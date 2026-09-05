@extends('layouts.authenticated.app')

@section('title', 'Room Management')

@section('content')
    {{-- <x-common.page-breadcrumb pageTitle="Room Management" /> --}}

    @php
        $floorLabels = [
            '1' => 'First Floor',
            '2' => 'Second Floor',
            '3' => 'Third Floor',
            '4' => 'Fourth Floor',
        ];

        $roomTypes = [
    'Standard Room' => 1500,
    'Standard Premium Room' => 1900,
    'Family Room' => 2700,
];

        $statusConfig = [
            'Available' => [
                'dot' => 'bg-emerald-500',
                'badge' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-500/30',
                'border' => 'border-emerald-100 dark:border-emerald-500/20'
            ],
            'Occupied' => [
                'dot' => 'bg-rose-500',
                'badge' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:ring-rose-500/30',
                'border' => 'border-rose-100 dark:border-rose-500/20'
            ],
            'Maintenance' => [
                'dot' => 'bg-amber-500',
                'badge' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-500/30',
                'border' => 'border-amber-100 dark:border-amber-500/20'
            ],
            'Reserved' => [
                'dot' => 'bg-blue-500',
                'badge' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/30',
                'border' => 'border-blue-100 dark:border-blue-500/20'
            ],
        ];

        $roomsByFloor = $rooms->groupBy('floor')->sortKeys();
    @endphp

    <div
        x-data="{
            /* modal visibility */
            statusModalOpen: false,
            addRoomModalOpen: false,
            editRoomModalOpen: false,
            deleteRoomModalOpen: false,

            /* status modal state */
            modalRoomNo: '',
            modalRoomMeta: '',
            modalGuestName: '',
            modalStayDates: '',
            modalStatus: 'Available',
            statusFormAction: '',

            /* edit modal state */
            editRoomId: null,
            edit_room_no: '',
            edit_floor: '',
            edit_room_type: '',
            edit_base_price: '',
            edit_status: 'Available',
            editFormAction: '',

            /* delete modal state */
            deleteRoomNo: '',
            deleteFormAction: '',

            /* filters */
            filterFloor: '',
            filterStatus: '',
            filterRoomType: '',

            baseUrl: '{{ url('room-management') }}',

            openStatusModal(no, type, price, status, id, guestName, stayDates) {
                this.modalRoomNo = no;
                this.modalRoomMeta = type + ' · ₱' + Number(price).toLocaleString();
                this.modalGuestName = guestName;
                this.modalStayDates = stayDates;
                this.modalStatus = status;
                this.statusFormAction = this.baseUrl + '/' + id + '/status';
                this.statusModalOpen = true;
            },

            openEditModal(id, roomNo, floor, roomType, basePrice, status) {
                this.editRoomId = id;
                this.edit_room_no = roomNo;
                this.edit_floor = String(floor);
                this.edit_room_type = roomType;
                this.edit_base_price = basePrice;
                this.edit_status = status;
                this.editFormAction = this.baseUrl + '/' + id;
                this.editRoomModalOpen = true;
            },

            openDeleteModal(id, roomNo) {
                this.deleteRoomNo = 'Room ' + roomNo;
                this.deleteFormAction = this.baseUrl + '/' + id;
                this.deleteRoomModalOpen = true;
            },

            closeAllModals() {
                this.statusModalOpen = false;
                this.addRoomModalOpen = false;
                this.editRoomModalOpen = false;
                this.deleteRoomModalOpen = false;
            },

            /* filtering */
            roomVisible(floor, status, type) {
                return (this.filterFloor === '' || this.filterFloor === floor)
                    && (this.filterStatus === '' || this.filterStatus === status)
                    && (this.filterRoomType === '' || this.filterRoomType === type);
            },

            hasActiveFilter() {
                return this.filterFloor !== '' || this.filterStatus !== '' || this.filterRoomType !== '';
            },

            resetFilters() {
                this.filterFloor = '';
                this.filterStatus = '';
                this.filterRoomType = '';
            }
        }"
        @keydown.escape.window="closeAllModals()"
    >

        {{-- STATUS MODAL --}}
        <div
            x-show="statusModalOpen"
            x-cloak
            x-transition.opacity
            @click.self="statusModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
            role="dialog"
            aria-modal="true"
        >
            <div class="w-full max-w-sm rounded-2xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-700 dark:bg-gray-900">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Update Room Status
                    </h3>

                    <button
                        type="button"
                        @click="statusModalOpen = false"
                        class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                        aria-label="Close"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" :action="statusFormAction" data-confirm-leave>
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Room
                        </label>

                        <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="modalRoomNo">—</p>
                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400" x-text="modalRoomMeta">—</p>

                        <div class="mt-4 rounded-xl bg-gray-50 px-3 py-2.5 dark:bg-gray-800">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200" x-text="modalGuestName"></p>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400" x-text="modalStayDates"></p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="modalStatus"
                            class="mb-1.5 block text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            New Status
                        </label>

                        <select
                            id="modalStatus"
                            name="status"
                            x-model="modalStatus"
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
                            @click="statusModalOpen = false"
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

        {{-- ADD ROOM MODAL --}}

<div
    x-show="addRoomModalOpen"
    x-cloak
    x-transition.opacity
    @click.self="addRoomModalOpen = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
>
    <div
        class="w-full max-w-2xl overflow-y-auto rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-900"
        style="max-height: 90vh;"
    >

        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 dark:border-gray-800">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Add New Room
                </h3>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Enter the details for the new room.
                </p>
            </div>

            <button
                type="button"
                @click="addRoomModalOpen = false"
                class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                aria-label="Close"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <form
            method="POST"
            action="{{ route('room.store') }}"
            data-confirm-leave
            class="p-6"
        >
            @csrf

            <div
                class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                x-data="{
                    roomTypes: <?= htmlspecialchars(json_encode($roomTypes), ENT_QUOTES, 'UTF-8') ?>,
                    selectedRoom: '',
                    price: ''
                }"
            >

                <!-- Room Number -->
                <div>
                    <label
                        for="add_room_no"
                        class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        Room Number
                    </label>

                    <input
                        type="text"
                        id="add_room_no"
                        name="room_no"
                        required
                        placeholder="e.g. 101"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                    >
                </div>

                <!-- Floor -->
                <div>
                    <label
                        for="add_floor"
                        class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        Floor
                    </label>

                    <select
                        id="add_floor"
                        name="floor"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                    >
                        <option value="">Select Floor</option>

                        @foreach ($floorLabels as $floor => $label)
                            <option value="{{ $floor }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Room Type -->
                <div>
                    <label
                        for="add_room_type"
                        class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        Room Type
                    </label>

                    <select
                        id="add_room_type"
                        name="room_type"
                        x-model="selectedRoom"
                        @change="price = roomTypes[selectedRoom] ? parseFloat(roomTypes[selectedRoom]).toFixed(2) : ''"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                    >
                        <option value="" disabled>
                            Select a room type
                        </option>

                        <template
                            x-for="(roomPrice, roomName) in roomTypes"
                            :key="roomName"
                        >
                            <option
                                :value="roomName"
                                x-text="`${roomName} (₱${Number(roomPrice).toLocaleString()})`"
                            ></option>
                        </template>
                    </select>
                </div>

                <!-- Base Price -->
                <div>
                    <label
                        for="add_base_price"
                        class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        Base Price
                    </label>

                    <div class="relative">
                        <span
                            class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400"
                        >
                            ₱
                        </span>

                        <input
                            type="number"
                            id="add_base_price"
                            name="base_price"
                            x-model="price"
                            min="0"
                            step="0.01"
                            required
                            placeholder="0.00"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-8 pr-3 text-sm font-medium text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                        >
                    </div>
                </div>

                <!-- Status -->
                <div class="sm:col-span-2">
                    <label
                        for="add_status"
                        class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        Status
                    </label>

                    <select
                        id="add_status"
                        name="status"
                        required
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                    >
                        <option value="Available">
                            Available
                        </option>

                        <option value="Occupied">
                            Occupied
                        </option>

                        <option value="Maintenance">
                            Under Maintenance
                        </option>

                        <option value="Reserved">
                            Reserved
                        </option>
                    </select>
                </div>

            </div>

            <!-- Actions -->
            <div class="mt-6 flex gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">
                <button
                    type="button"
                    @click="addRoomModalOpen = false"
                    class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="flex-1 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Add Room
                </button>
            </div>

        </form>
    </div>
</div>


        {{-- EDIT ROOM MODAL --}}

<div
    x-show="editRoomModalOpen"
    x-cloak
    x-transition.opacity
    @click.self="editRoomModalOpen = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
>
    <div
        class="w-full max-w-2xl overflow-y-auto rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-900"
        style="max-height: 90vh;"
    >

        <!-- Header -->
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-5 dark:border-gray-800">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Edit Room
                </h3>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Update the room information below.
                </p>
            </div>

            <button
                type="button"
                @click="editRoomModalOpen = false"
                class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                aria-label="Close"
            >
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form
            method="POST"
            :action="editFormAction"
            data-confirm-leave
            class="p-6"
        >
            @csrf
            @method('PUT')

            <div
                class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                x-data="{
                    roomTypes: <?= htmlspecialchars(json_encode($roomTypes), ENT_QUOTES, 'UTF-8') ?>,
                    edit_room_type: '',
                    edit_base_price: ''
                }"
                x-init="
                    edit_room_type = '{{ old('room_type', '') }}';
                    edit_base_price = '{{ old('base_price', '') }}';
                "
            >

                <!-- Room Number -->
                <div>
                    <label
                        for="edit_room_no"
                        class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        Room Number
                    </label>

                    <input
                        type="text"
                        id="edit_room_no"
                        name="room_no"
                        required
                        x-model="edit_room_no"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                    >
                </div>

                <!-- Floor -->
                <div>
                    <label
                        for="edit_floor"
                        class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        Floor
                    </label>

                    <select
                        id="edit_floor"
                        name="floor"
                        required
                        x-model="edit_floor"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                    >
                        @foreach ($floorLabels as $floor => $label)
                            <option value="{{ $floor }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Room Type -->
                <div>
                    <label
                        for="edit_room_type"
                        class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        Room Type
                    </label>

                    <select
                        id="edit_room_type"
                        name="room_type"
                        required
                        x-model="edit_room_type"
                        @change="
                            edit_base_price = roomTypes[edit_room_type]
                                ? parseFloat(roomTypes[edit_room_type]).toFixed(2)
                                : ''
                        "
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                    >
                        <option value="" disabled>
                            Select a room type
                        </option>

                        <template
                            x-for="(roomPrice, roomName) in roomTypes"
                            :key="roomName"
                        >
                            <option
                                :value="roomName"
                                x-text="`${roomName} (₱${Number(roomPrice).toLocaleString()})`"
                            ></option>
                        </template>
                    </select>
                </div>

                <!-- Base Price -->
                <div>
                    <label
                        for="edit_base_price"
                        class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        Base Price
                    </label>

                    <div class="relative">
                        <span
                            class="absolute left-3 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400"
                        >
                            ₱
                        </span>

                        <input
                            type="number"
                            id="edit_base_price"
                            name="base_price"
                            min="0"
                            step="0.01"
                            required
                            x-model="edit_base_price"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-8 pr-3 text-sm font-medium text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                        >
                    </div>
                </div>

                <!-- Status -->
                <div class="sm:col-span-2">
                    <label
                        for="edit_status"
                        class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400"
                    >
                        Status
                    </label>

                    <select
                        id="edit_status"
                        name="status"
                        required
                        x-model="edit_status"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm font-medium text-gray-800 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-indigo-500/30"
                    >
                        <option value="Available">
                            Available
                        </option>

                        <option value="Occupied">
                            Occupied
                        </option>

                        <option value="Maintenance">
                            Under Maintenance
                        </option>

                        <option value="Reserved">
                            Reserved
                        </option>
                    </select>
                </div>

            </div>

            <!-- Actions -->
            <div class="mt-6 flex gap-3 border-t border-gray-100 pt-5 dark:border-gray-800">

                <button
                    type="button"
                    @click="editRoomModalOpen = false"
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


        {{-- DELETE CONFIRMATION MODAL --}}
        <div
            x-show="deleteRoomModalOpen"
            x-cloak
            x-transition.opacity
            @click.self="deleteRoomModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
            role="dialog"
            aria-modal="true"
        >
            <div class="w-full max-w-sm rounded-2xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-700 dark:bg-gray-900">

                <div class="mb-5 flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m-4 0h14" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Delete Room
                        </h3>

                        <p class="mt-1 text-sm leading-5 text-gray-500 dark:text-gray-400">
                            Are you sure you want to delete
                            <span class="font-semibold text-gray-700 dark:text-gray-300" x-text="deleteRoomNo"></span>?
                            This action cannot be undone.
                        </p>
                    </div>
                </div>

                <form method="POST" :action="deleteFormAction">
                    @csrf
                    @method('DELETE')

                    <div class="flex gap-3">
                        <button
                            type="button"
                            @click="deleteRoomModalOpen = false"
                            class="flex-1 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="flex-1 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700 focus:ring-2 focus:ring-rose-500 focus:ring-offset-2"
                        >
                            Delete Room
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <x-common.toast-notification />
        {{-- @if (session('success'))
            <x-ui.alert variant="success" title="Success" :message="session('success')" />
        @endif --}}

        <div class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
            <div class="mx-auto w-full">

                {{-- HEADER --}}
                <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Room Management
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $rooms->count() }} rooms across {{ $rooms->pluck('floor')->unique()->count() }} floors
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex flex-wrap items-center gap-4 text-xs font-medium">
                            <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                Available
                            </span>

                            <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                                <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                                Occupied
                            </span>

                            <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                Maintenance
                            </span>

                            <span class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                                Reserved
                            </span>
                        </div>

                        {{-- ADD ROOM BUTTON --}}
                        <button
                            type="button"
                            @click="addRoomModalOpen = true"
                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Add New Room
                        </button>
                    </div>
                </div>

                {{-- FILTER INTERFACE --}}
                <div class="mb-8 rounded-2xl border border-gray-200 bg-gray-50/70 p-4 dark:border-gray-800 dark:bg-white/[0.02]">
                    <div class="mb-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414A1 1 0 0014 14.414V19l-4 2v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>

                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Filter Rooms
                            </span>
                        </div>

                        <button
                            type="button"
                            @click="resetFilters()"
                            class="text-xs font-semibold text-indigo-600 transition hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300"
                        >
                            Reset Filters
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

                        <div>
                            <label for="floorFilter"
                                class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">
                                Floor
                            </label>

                            <select
                                id="floorFilter"
                                x-model="filterFloor"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:ring-indigo-500/30"
                            >
                                <option value="">All Floors</option>

                                @foreach ($rooms->pluck('floor')->unique()->sort() as $floor)
                                    <option value="{{ $floor }}">
                                        {{ $floorLabels[$floor] ?? "Floor $floor" }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="statusFilter"
                                class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">
                                Status
                            </label>

                            <select
                                id="statusFilter"
                                x-model="filterStatus"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:ring-indigo-500/30"
                            >
                                <option value="">All Status</option>
                                <option value="Available">Available</option>
                                <option value="Occupied">Occupied</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Reserved">Reserved</option>
                            </select>
                        </div>

                        <div>
                            <label for="roomTypeFilter"
                                class="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">
                                Room Type
                            </label>

                            <select
                                id="roomTypeFilter"
                                x-model="filterRoomType"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:ring-indigo-500/30"
                            >
                                <option value="">All Room Types</option>

                                @foreach ($rooms->pluck('room_type')->filter()->unique()->sort() as $type)
                                    <option value="{{ $type }}">
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div
                        x-show="hasActiveFilter()"
                        x-cloak
                        class="mt-3 text-xs font-medium text-gray-500 dark:text-gray-400"
                        x-text="rooms.filter(r => roomVisible(r.floor, r.status, r.roomType)).length + ' room' + (rooms.filter(r => roomVisible(r.floor, r.status, r.roomType)).length === 1 ? '' : 's') + ' found'"
                        x-data="{
                            rooms: {{ Illuminate\Support\Js::from($rooms->map(fn ($r) => ['floor' => (string) $r->floor, 'status' => $r->status, 'roomType' => $r->room_type])) }}
                        }"
                    ></div>
                </div>

                {{-- ROOMS --}}
                <div class="space-y-10">

                    @foreach ($roomsByFloor as $floor => $floorRooms)

                        <div
                            class="room-floor-section"
                            x-show="floorRooms.filter(r => roomVisible(r.floor, r.status, r.roomType)).length > 0"
                            x-cloak
                            x-data="{
                                floorRooms: {{ Illuminate\Support\Js::from($floorRooms->map(fn ($r) => ['floor' => (string) $r->floor, 'status' => $r->status, 'roomType' => $r->room_type])) }}
                            }"
                        >
                            <div class="mb-4 flex items-center gap-3">
                                <h4 class="text-sm font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                    {{ $floorLabels[$floor] ?? "Floor $floor" }}
                                </h4>

                                <div class="h-px flex-1 bg-gray-100 dark:bg-gray-800"></div>

                                <span
                                    class="text-xs font-medium text-gray-400 dark:text-gray-600"
                                    x-text="(() => { const n = floorRooms.filter(r => roomVisible(r.floor, r.status, r.roomType)).length; return n + (n === 1 ? ' room' : ' rooms'); })()"
                                ></span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6">

                                @foreach ($floorRooms as $room)

                                    @php
                                        $cfg = $statusConfig[$room->status] ?? $statusConfig['Available'];
                                        $currentBooking = $room->currentBooking ?? $room->currentWalkInBooking;
                                        $guestName = $currentBooking
                                            ? ($currentBooking instanceof \App\Models\WalkInBooking
                                                ? ($currentBooking->fullname ?: 'Guest name unavailable')
                                                : (optional($currentBooking->user)->name ?: 'Guest name unavailable'))
                                            : 'No guest assigned';
                                        $stayDates = $currentBooking
                                            ? (optional($currentBooking->check_in)->format('M j, Y') ?? 'Date unavailable')
                                                . ' - ' . (optional($currentBooking->check_out)->format('M j, Y') ?? 'Date unavailable')
                                            : 'No stay details available';
                                    @endphp

                                    <div
                                        class="room-card group relative cursor-pointer rounded-2xl border bg-white p-4 shadow-sm transition-all duration-150 hover:-translate-y-0.5 hover:shadow-md dark:bg-gray-900 {{ $cfg['border'] }}"
                                        x-show="roomVisible('{{ $room->floor }}', '{{ $room->status }}', '{{ $room->room_type }}')"
                                        x-cloak
                                        @click="openStatusModal(
                                            @js($room->room_no),
                                            @js($room->room_type),
                                            @js($room->base_price),
                                            @js($room->status),
                                            @js($room->id),
                                            @js($guestName),
                                            @js($stayDates)
                                        )"
                                        role="button"
                                        tabindex="0"
                                        @keydown.enter="openStatusModal(
                                            @js($room->room_no),
                                            @js($room->room_type),
                                            @js($room->base_price),
                                            @js($room->status),
                                            @js($room->id),
                                            @js($guestName),
                                            @js($stayDates)
                                        )"
                                        title="Click to update status"
                                    >

                                        <span class="absolute right-3 top-3 h-2 w-2 rounded-full {{ $cfg['dot'] }}"></span>

                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ $room->room_no }}
                                        </p>

                                        <p class="mt-0.5 text-xs leading-tight text-gray-400 dark:text-gray-500">
                                            {{ $room->room_type }}
                                        </p>

                                        @if ($currentBooking && $guestName)
                                            <p class="mt-2 truncate text-sm font-semibold text-gray-700 dark:text-gray-300" title="{{ $guestName }}">
                                                {{ $guestName }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                                                {{ optional($currentBooking->check_in)->format('M j, Y') ?? '—' }}
                                                -
                                                {{ optional($currentBooking->check_out)->format('M j, Y') ?? '—' }}
                                            </p>
                                        @endif

                                        <p class="mt-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            ₱{{ number_format($room->base_price) }}
                                        </p>

                                        <span class="mt-3 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium {{ $cfg['badge'] }}">
                                            {{ $room->status }}
                                        </span>

                                        {{-- CARD ACTIONS --}}
                                        <div class="mt-4 flex gap-2">

                                            <button
                                                type="button"
                                                @click.stop="openEditModal(
                                                    @js($room->id),
                                                    @js($room->room_no),
                                                    @js($room->floor),
                                                    @js($room->room_type),
                                                    @js($room->base_price),
                                                    @js($room->status)
                                                )"
                                                class="flex-1 rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-xs font-semibold text-gray-600 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-indigo-500/30 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400"
                                            >
                                                <span class="inline-flex items-center justify-center gap-1">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>

                                                </span>
                                            </button>

                                            <button
                                                type="button"
                                                @click.stop="openDeleteModal(
                                                    @js($room->id),
                                                    @js($room->room_no)
                                                )"
                                                class="flex-1 rounded-lg border border-rose-100 bg-rose-50 px-2 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20"
                                            >
                                                <span class="inline-flex items-center justify-center gap-1">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3m-4 0h14" />
                                                    </svg>

                                                </span>
                                            </button>

                                        </div>

                                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center rounded-2xl bg-white/0 opacity-0 transition-all duration-150 group-hover:bg-black/[0.03] group-hover:opacity-100 dark:group-hover:bg-white/[0.03]">
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

    </div>
@endsection

@extends('layouts.authenticated.app')

@section('title', 'Pending Bookings')

@section('content')

<div x-data="{
    assignModal:false,
    cancelModal:false,
    deleteModal:false
}">

    <x-common.page-breadcrumb pageTitle="Pending Bookings" />

    <div class=" rounded-3xl border border-gray-200 bg-white px-6 py-8 shadow-sm">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Pending Bookings
                </h2>

                <p class="text-sm text-gray-500">
                    Manage and review incoming reservation requests.
                </p>
            </div>

            <div class="flex gap-3">

                <div class="rounded-2xl bg-yellow-50 px-4 py-3 text-center">
                    <p class="text-xs text-yellow-600">Pending</p>
                    <p class="text-lg font-bold text-yellow-700">24</p>
                </div>

            </div>

        </div>

        {{-- SEARCH --}}
        <div class="mb-6">
            <input
                type="text"
                placeholder="Search bookings..."
                class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none">
        </div>

        {{-- TABLE --}}
        <div class="overflow-hidden rounded-3xl border border-gray-200">

            <table class="w-full min-w-[1100px] text-sm">

                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500">

                        <th class="px-6 py-4">Guest</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Check-in</th>
                        <th class="px-6 py-4">Check-out</th>
                        <th class="px-6 py-4">Guests</th>
                        <th class="px-6 py-4">Room</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    {{-- ROW 1 --}}
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-600">
                                    JD
                                </div>

                                <div>
                                    <p class="font-semibold text-gray-800">Juan Dela Cruz</p>
                                    <p class="text-xs text-gray-500">REF-0001</p>
                                </div>

                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-600">09123456789</td>
                        <td class="px-6 py-4 text-gray-600">May 25, 2026</td>
                        <td class="px-6 py-4 text-gray-600">May 28, 2026</td>
                        <td class="px-6 py-4 text-gray-600">2</td>
                        <td class="px-6 py-4 text-gray-600">Deluxe</td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-2 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700 ring-1 ring-yellow-200">
                                <span class="h-2 w-2 rounded-full bg-yellow-500"></span>
                                Pending
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">

                                {{-- CONFIRM --}}
                                <button @click="assignModal=true"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-100">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7"/>
                                    </svg>

                                </button>

                                {{-- CANCEL --}}
                                <button @click="cancelModal=true"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-100">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M6 18L18 6M6 6l12 12"/>
                                    </svg>

                                </button>

                                {{-- DELETE --}}
                                <button @click="deleteModal=true"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-100">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M19 7L18 20H6L5 7M10 11v6M14 11v6M4 7h16M9 7V4h6v3"/>
                                    </svg>

                                </button>

                            </div>
                        </td>

                    </tr>

                    {{-- ROW 2 (same UI style) --}}
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-600">
                                    MS
                                </div>

                                <div>
                                    <p class="font-semibold text-gray-800">Maria Santos</p>
                                    <p class="text-xs text-gray-500">REF-0002</p>
                                </div>

                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-600">09987654321</td>
                        <td class="px-6 py-4 text-gray-600">June 01, 2026</td>
                        <td class="px-6 py-4 text-gray-600">June 04, 2026</td>
                        <td class="px-6 py-4 text-gray-600">4</td>
                        <td class="px-6 py-4 text-gray-600">Family Suite</td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-2 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700 ring-1 ring-yellow-200">
                                <span class="h-2 w-2 rounded-full bg-yellow-500"></span>
                                Pending
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">

                                {{-- CONFIRM --}}
                                <button @click="assignModal=true"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-100">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7"/>
                                    </svg>

                                </button>

                                {{-- CANCEL --}}
                                <button @click="cancelModal=true"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-100">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M6 18L18 6M6 6l12 12"/>
                                    </svg>

                                </button>

                                {{-- DELETE --}}
                                <button @click="deleteModal=true"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-100">

                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M19 7L18 20H6L5 7M10 11v6M14 11v6M4 7h16M9 7V4h6v3"/>
                                    </svg>

                                </button>

                            </div>
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

    {{-- ASSIGN MODAL --}}
    <div x-show="assignModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">

        <div @click.away="assignModal=false"
            class="w-full max-w-md rounded-3xl bg-white p-6">

            <h3 class="text-lg font-bold text-gray-800">Assign Room</h3>

            <p class="text-sm text-gray-500">Select a room to confirm booking.</p>

            <select class="mt-4 w-full rounded-xl border border-gray-200 px-4 py-3">
                <option>Room 101 - Deluxe</option>
                <option>Room 102 - Deluxe</option>
                <option>Room 201 - Suite</option>
            </select>

            <div class="mt-6 flex justify-end gap-3">

                <button @click="assignModal=false"
                    class="rounded-xl border px-4 py-2 text-gray-600">
                    Close
                </button>

                <button class="rounded-xl bg-green-600 px-4 py-2 text-white">
                    Confirm
                </button>

            </div>

        </div>

    </div>

    {{-- CANCEL MODAL --}}
    <div x-show="cancelModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">

        <div @click.away="cancelModal=false"
            class="w-full max-w-md rounded-3xl bg-white p-6">

            <h3 class="text-lg font-bold text-amber-600">Cancel Booking?</h3>
            <p class="mt-2 text-sm text-gray-500">This action will mark booking as cancelled.</p>

            <div class="mt-6 flex justify-end gap-3">

                <button @click="cancelModal=false" class="rounded-xl border px-4 py-2">
                    No
                </button>

                <button class="rounded-xl bg-amber-500 px-4 py-2 text-white">
                    Yes Cancel
                </button>

            </div>

        </div>

    </div>

    {{-- DELETE MODAL --}}
    <div x-show="deleteModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">

        <div @click.away="deleteModal=false"
            class="w-full max-w-md rounded-3xl bg-white p-6">

            <h3 class="text-lg font-bold text-red-600">Delete Booking?</h3>
            <p class="mt-2 text-sm text-gray-500">
                This action cannot be undone.
            </p>

            <div class="mt-6 flex justify-end gap-3">

                <button @click="deleteModal=false" class="rounded-xl border px-4 py-2">
                    Cancel
                </button>

                <button class="rounded-xl bg-red-600 px-4 py-2 text-white">
                    Delete
                </button>

            </div>

        </div>

    </div>

</div>

@endsection

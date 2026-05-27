@extends('layouts.authenticated.app')

@section('title', 'Confirmed Bookings')

@section('content')

<div x-data="{
    assignModal:false,
    cancelModal:false,
    deleteModal:false
}">

    <x-common.page-breadcrumb pageTitle="Confirmed Bookings" />

    <div class=" rounded-3xl border border-gray-200 bg-white px-6 py-8 shadow-sm">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Confirmed Bookings
                </h2>

                <p class="text-sm text-gray-500">
                    Manage and review incoming reservation requests.
                </p>
            </div>

            <div class="flex gap-3">

                <div class="rounded-2xl bg-green-50 px-4 py-3 text-center">
                    <p class="text-xs text-green-600">Confirmed</p>
                    <p class="text-lg font-bold text-green-700">24</p>
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
                        <th class="px-6 py-4">Room</th>
                        <th class="px-6 py-4">Guest</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Check-in</th>
                        <th class="px-6 py-4">Check-out</th>
                        <th class="px-6 py-4">Guests</th>
                        <th class="px-6 py-4">Room</th>
                        <th class="px-6 py-4">Status</th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    {{-- ROW 1 --}}
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 text-gray-600 font-bold">Room 101</td>

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
                            <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-green-200">
                                <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                Confirmed
                            </span>
                        </td>

                    </tr>

                    {{-- ROW 2 (same UI style) --}}
                    <tr class="hover:bg-gray-50">

                        <td class="px-6 py-4 text-gray-600 font-bold">Room 102</td>

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
                            <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-green-200">
                                <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                Confirmed
                            </span>
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>


</div>

@endsection

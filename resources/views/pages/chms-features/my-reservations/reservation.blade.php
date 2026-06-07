@extends('layouts.authenticated.app')

@section('title', 'My Reservations')

@section('content')
    <x-common.page-breadcrumb pageTitle="My Reservations" />
    <div
        class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
        <div class="">
            <div class=" grid grid-cols-1 gap-6 lg:grid-cols-2 text-left">
                {{-- PENDING --}}
                <div class="rounded-2xl border border-yellow-200 bg-white shadow-sm">
                    <div class="border-b border-yellow-100 bg-yellow-50 px-5 py-4 rounded-t-2xl">
                        <h2 class="text-lg font-semibold text-yellow-700">
                            Pending Reservations
                        </h2>
                        <p class="text-xs text-yellow-600">
                            Waiting for confirmation
                        </p>
                    </div>

                    <div class="p-5 space-y-4">
                        {{-- SAMPLE CARD --}}
                        <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Room Deluxe Suite</p>
                                    <p class="text-xs text-gray-500">Check-in: June 10, 2026</p>
                                    <p class="text-xs text-gray-500">Check-out: June 12, 2026</p>
                                </div>

                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                    Pending
                                </span>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs text-gray-400">Ref: CH-ABC12345</span>
                                <button class="text-xs font-medium text-yellow-600 hover:text-yellow-700">
                                    View
                                </button>
                            </div>
                        </div>

                        {{-- Repeat cards here --}}
                        <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Room Deluxe Suite</p>
                                    <p class="text-xs text-gray-500">Check-in: June 10, 2026</p>
                                    <p class="text-xs text-gray-500">Check-out: June 12, 2026</p>
                                </div>

                                <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                    Pending
                                </span>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs text-gray-400">Ref: CH-ABC12345</span>
                                <button class="text-xs font-medium text-yellow-600 hover:text-yellow-700">
                                    View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONFIRMED --}}
                <div class="rounded-2xl border border-green-200 bg-white shadow-sm">
                    <div class="border-b border-green-100 bg-green-50 px-5 py-4 rounded-t-2xl">
                        <h2 class="text-lg font-semibold text-green-700">
                            Confirmed Reservations
                        </h2>
                        <p class="text-xs text-green-600">
                            Approved bookings ready for stay
                        </p>
                    </div>

                    <div class="p-5 space-y-4">
                        {{-- SAMPLE CARD --}}
                        <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Room Standard Twin</p>
                                    <p class="text-xs text-gray-500">Check-in: June 15, 2026</p>
                                    <p class="text-xs text-gray-500">Check-out: June 18, 2026</p>
                                </div>

                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                    Confirmed
                                </span>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs text-gray-400">Ref: CH-ZXY98765</span>
                                <button class="text-xs font-medium text-green-600 hover:text-green-700">
                                    View
                                </button>
                            </div>
                        </div>

                        {{-- Repeat cards here --}}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

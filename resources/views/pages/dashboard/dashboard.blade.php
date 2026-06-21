@extends('layouts.authenticated.app')

@push('styles')

@endpush


@section('content')

    @php
        $showWarning = auth()->user()->is_google_user
            && !auth()->user()->has_changed_password
            && auth()->user()->first_google_login_at
            && auth()->user()->first_google_login_at->gt(now()->subDays(1));
    @endphp

    @if($showWarning)
        <div class="bg-blue-100 dark:bg-gradient-to-br from-amber-300 to-yellow-200 border border-blue-400 dark:border-amber-600 text-black dark:text-black px-4 py-3 rounded relative mb-4" role="alert" id="google-login-warning">
            <strong class="font-bold">Welcome!</strong>
            <span class="block sm:inline">It looks like you signed in with Google. For security reasons, please update your password by <a href="{{ route('profile') }}" class="underline text-blue-400 dark:text-blue-400">clicking here</a>.</span>
        </div>
    @endif


    @role('client')
    <div class="grid grid-cols-12 gap-6">

        {{-- Welcome Card --}}
        <div class="col-span-12">
            <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-lg backdrop-blur-lg">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-yellow-300 shadow-sm shadow-amber-500/30">
                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="h-full w-full rounded-2xl object-cover">
                            @else
                                <span class="text-lg font-bold text-white">
                                    {{ auth()->user()->getInitialsAttribute() }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm text-black dark:text-gray-400">
                                Welcome back, <span class="font-medium">{{ auth()->user()->name }} !</span>
                            </p>
                            <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                Your Booking Dashboard
                            </h2>
                            <p class="mt-1 text-xs text-gray-500">
                                Track reservations, room status, and recent activity.
                            </p>
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="flex shrink-0 gap-3">
                        <div class="rounded-2xl bg-green-500/10 px-5 py-3 text-center">
                            <p class="text-xs font-semibold uppercase tracking-widest text-green-400">Active</p>
                            <h4 class="mt-1 text-2xl font-bold text-green-400">3</h4>
                        </div>
                        <div class="rounded-2xl bg-blue-500/10 px-5 py-3 text-center">
                            <p class="text-xs font-semibold uppercase tracking-widest text-blue-400">Done</p>
                            <h4 class="mt-1 text-2xl font-bold text-blue-400">12</h4>
                        </div>
                        <div class="rounded-2xl bg-yellow-500/10 px-5 py-3 text-center">
                            <p class="text-xs font-semibold uppercase tracking-widest text-yellow-400">Pending</p>
                            <h4 class="mt-1 text-2xl font-bold text-yellow-400">1</h4>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Upcoming Bookings --}}
        <div class="col-span-12 md:col-span-6">
            <div class="flex h-full flex-col rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-lg transition hover:border-white/20 hover:shadow-xl">

                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-500 dark:text-white">Upcoming Bookings</h3>
                        <p class="mt-1 text-sm text-gray-500">Your upcoming reservations and schedules.</p>
                    </div>
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-500/10">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-5 flex flex-col gap-3">

                    <div class="flex flex-col gap-3 rounded-2xl border border-blue-500/20 bg-blue-500/10 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-blue-400">Room Booking</p>
                            <h4 class="mt-1 font-semibold text-gray-600 dark:text-white">Conference Room A</h4>
                            <p class="mt-1 text-sm text-gray-400">May 10, 2026 &bull; 10:00 AM</p>
                        </div>
                        <span class="w-fit rounded-full bg-blue-500/20 px-3 py-1 text-xs font-semibold text-blue-300">Tomorrow</span>
                    </div>

                    <div class="flex flex-col gap-3 rounded-2xl border border-green-500/20 bg-green-500/10 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-green-400">Check-in</p>
                            <h4 class="mt-1 font-semibold text-gray-600 dark:text-white">Suite 204</h4>
                            <p class="mt-1 text-sm text-gray-400">May 12, 2026 &bull; 2:00 PM</p>
                        </div>
                        <span class="w-fit rounded-full bg-green-400 dark:bg-green-500/20 px-3 py-1 text-xs font-semibold text-green-300">In 2 days</span>
                    </div>

                </div>
            </div>
        </div>

        {{-- Booking History --}}
        <div class="col-span-12 md:col-span-6">
            <div class="flex h-full flex-col rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-lg transition hover:border-white/20 hover:shadow-xl">

                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-500 dark:text-white">Booking History</h3>
                        <p class="mt-1 text-sm text-gray-500">Recently completed reservations and activity.</p>
                    </div>
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-500/10">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-5 flex flex-col gap-3">

                    <div class="flex flex-col gap-3 rounded-2xl border border-white/[0.06] bg-white/[0.04] p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">Completed Appointment</p>
                            <h4 class="mt-1 font-semibold text-gray-600 dark:text-white">Wellness Check</h4>
                            <p class="mt-1 text-sm text-gray-400">May 6, 2026 &bull; 1:00 PM</p>
                        </div>
                        <span class="w-fit rounded-full bg-white/[0.06] px-3 py-1 text-xs font-semibold text-gray-400">Yesterday</span>
                    </div>

                    <div class="flex flex-col gap-3 rounded-2xl border border-white/[0.06] bg-white/[0.04] p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-gray-500">Past Reservation</p>
                            <h4 class="mt-1 font-semibold text-gray-600 dark:text-white">Parking Bay 12</h4>
                            <p class="mt-1 text-sm text-gray-400">May 5, 2026 &bull; 9:00 AM</p>
                        </div>
                        <span class="w-fit rounded-full bg-white/[0.06] px-3 py-1 text-xs font-semibold text-gray-400">2 days ago</span>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- popup for reference number --}}
@if($referenceNumber)
<div
    x-data="{ open: true }"
    x-init="setTimeout(() => open = true, 100)"
>
    <!-- Overlay -->
    <div
        x-show="open"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
        <!-- Modal -->
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 relative"
            @click.away="open = false"
        >
            <!-- Content -->
            <h2 class="text-xl font-semibold text-gray-800 mb-2">
                Booking Confirmed
            </h2>

            <p class="text-gray-600 mb-4">
                Your reference number is:
            </p>

            <div class="text-2xl font-bold text-indigo-600 mb-4">
                {{ $referenceNumber }}
            </div>

            <p class="text-sm text-gray-500">
                Please check your email for full booking details. You can also view your booking history and details in your
                <a href="{{ route('dashboard') }}" class="underline text-indigo-600 hover:text-indigo-800">
                    My Reservations
                </a>.
            </p>

            <div class="mt-5 text-right">
                <button
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
                    @click="open = false"
                >
                    OK
                </button>
            </div>
        </div>
    </div>
</div>
@endif
    @endrole

    @role('staff')
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-sm backdrop-blur-sm">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 shadow-lg shadow-indigo-500/30">

                            @if (auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar"
                                    class="h-full w-full rounded-2xl object-cover">
                            @else
                                <span class="text-lg font-bold text-white">
                                    {{ auth()->user()->getInitialsAttribute() }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm text-black dark:text-gray-400">
                                Logged in as, <span class="font-medium text-black dark:text-white">{{ auth()->user()->name }}</span>
                            </p>

                            <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-600 dark:text-white">Staff Dashboard</h2>

                            <p class="mt-1 max-w-2xl text-xs leading-relaxed text-gray-500">
                                Manage rooms, bookings, check-ins, and approvals efficiently.
                            </p>
                        </div>
                    </div>

                </div>

                {{-- Summary Stats --}}
                <div class="mt-6 grid grid-cols-3 gap-4 sm:grid-cols-3 xl:grid-cols-5">

                    <div class="rounded-2xl border border-gray/5 bg-white/[0.05] px-4 py-4 text-center">
                        <p class="text-[11px] font-semibold uppercase tracking-widest text-black dark:text-gray-400">
                            Total Rooms
                        </p>
                        <h4 class="mt-2 text-2xl font-bold text-gray-600 dark:text-white">24</h4>
                    </div>

                    <div class="rounded-2xl border border-green-500/10 bg-green-500/10 px-4 py-4 text-center">
                        <p class="text-[11px] font-semibold uppercase tracking-widest text-green-600 dark:text-green-400">
                            Available
                        </p>
                        <h4 class="mt-2 text-2xl font-bold text-green-600 dark:text-green-300">14</h4>
                    </div>

                    <div class="rounded-2xl border border-blue-500/10 bg-blue-500/10 px-4 py-4 text-center">
                        <p class="text-[11px] font-semibold uppercase tracking-widest text-blue-400">
                            Bookings Today
                        </p>
                        <h4 class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-300">8</h4>
                    </div>

                    <div class="rounded-2xl border border-yellow-500/10 bg-yellow-500/10 px-4 py-4 text-center">
                        <p class="text-[11px] font-semibold uppercase tracking-widest text-yellow-400">
                            Pending
                        </p>
                        <h4 class="mt-2 text-2xl font-bold text-yellow-600 dark:text-yellow-300">3</h4>
                    </div>

                    <div class="rounded-2xl border border-indigo-500/10 bg-indigo-500/10 px-4 py-4 text-center">
                        <p class="text-[11px] font-semibold uppercase tracking-widest text-indigo-400">
                            Checked-in
                        </p>
                        <h4 class="mt-2 text-2xl font-bold text-indigo-600 dark:text-indigo-300">9</h4>
                    </div>

                    {{-- <div class="rounded-2xl border border-violet-500/10 bg-violet-500/10 px-4 py-4 text-center">
                        <p class="text-[11px] font-semibold uppercase tracking-widest text-violet-400">
                            Revenue
                        </p>
                        <h4 class="mt-2 text-2xl font-bold text-violet-300">₱12.4k</h4>
                    </div> --}}

                </div>

            </div>
        </div>

        <div class="col-span-12">
            <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-sm">

                <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                    <div>
                        <h3 class="font-semibold text-gray-600 dark:text-white">
                            Room Overview
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Live status of all rooms.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-green-400"></span>
                            Available
                        </span>

                        <span class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-red-400"></span>
                            Occupied
                        </span>

                        <span class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-yellow-400"></span>
                            Maintenance
                        </span>
                    </div>

                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px] text-sm">

                        <thead>
                            <tr
                                class="border-b border-white/[0.06] text-left text-xs font-semibold uppercase tracking-widest text-gray-500">
                                <th class="pb-3 pr-4">Room</th>
                                <th class="pb-3 pr-4">Type / Capacity</th>
                                <th class="pb-3 pr-4">Status</th>
                                <th class="pb-3 pr-4">Current Guest</th>
                                <th class="pb-3 pr-4">Check-in</th>
                                <th class="pb-3">Check-out</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-white/[0.04]">

                            {{-- Available --}}
                            <tr class="group transition hover:bg-white/[0.02]">
                                <td class="py-4 pr-4 font-semibold text-gray-800 dark:text-white">Room 101</td>
                                <td class="py-4 pr-4 text-gray-400">Standard &bull; 2 pax</td>
                                <td class="py-4 pr-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-green-500/10 px-2.5 py-1 text-xs font-semibold text-green-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-400"></span>
                                        Available
                                    </span>
                                </td>
                                <td class="py-4 pr-4 text-gray-500">—</td>
                                <td class="py-4 pr-4 text-gray-500">—</td>
                                <td class="py-4 text-gray-500">—</td>
                            </tr>

                            {{-- Occupied --}}
                            <tr class="group transition hover:bg-white/[0.02]">
                                <td class="py-4 pr-4 font-semibold text-gray-800 dark:text-white">Suite 204</td>
                                <td class="py-4 pr-4 text-gray-400">Suite &bull; 4 pax</td>
                                <td class="py-4 pr-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-red-500/10 px-2.5 py-1 text-xs font-semibold text-red-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                        Occupied
                                    </span>
                                </td>
                                <td class="py-4 pr-4 text-gray-300">Maria Santos</td>
                                <td class="py-4 pr-4 text-gray-400">May 8 &bull; 2:00 PM</td>
                                <td class="py-4 text-gray-400">May 10 &bull; 12:00 PM</td>
                            </tr>

                            {{-- Maintenance --}}
                            <tr class="group transition hover:bg-white/[0.02]">
                                <td class="py-4 pr-4 font-semibold text-gray-800 dark:text-white">Room 305</td>
                                <td class="py-4 pr-4 text-gray-400">Deluxe &bull; 2 pax</td>
                                <td class="py-4 pr-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-yellow-500/10 px-2.5 py-1 text-xs font-semibold text-yellow-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-yellow-400"></span>
                                        Maintenance
                                    </span>
                                </td>
                                <td class="py-4 pr-4 text-gray-500">—</td>
                                <td class="py-4 pr-4 text-gray-500">—</td>
                                <td class="py-4 text-gray-500">—</td>
                            </tr>

                            {{-- Occupied --}}
                            <tr class="group transition hover:bg-white/[0.02]">
                                <td class="py-4 pr-4 font-semibold text-gray-800 dark:text-white">Room 112</td>
                                <td class="py-4 pr-4 text-gray-400">Standard &bull; 2 pax</td>
                                <td class="py-4 pr-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-red-500/10 px-2.5 py-1 text-xs font-semibold text-red-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                        Occupied
                                    </span>
                                </td>
                                <td class="py-4 pr-4 text-gray-300">Juan dela Cruz</td>
                                <td class="py-4 pr-4 text-gray-400">May 7 &bull; 3:00 PM</td>
                                <td class="py-4 text-gray-400">May 9 &bull; 11:00 AM</td>
                            </tr>

                            {{-- Available --}}
                            <tr class="group transition hover:bg-white/[0.02]">
                                <td class="py-4 pr-4 font-semibold text-gray-800 dark:text-white">Conference A</td>
                                <td class="py-4 pr-4 text-gray-400">Conference &bull; 20 pax</td>
                                <td class="py-4 pr-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-green-500/10 px-2.5 py-1 text-xs font-semibold text-green-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-400"></span>
                                        Available
                                    </span>
                                </td>
                                <td class="py-4 pr-4 text-gray-500">—</td>
                                <td class="py-4 pr-4 text-gray-500">—</td>
                                <td class="py-4 text-gray-500">—</td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6 flex flex-col gap-4 border-t border-white/[0.06] pt-4 sm:flex-row sm:items-center sm:justify-between">

                    <p class="text-sm text-gray-500">
                        Showing
                        <span class="font-medium text-black dark:text-white">1</span>
                        to
                        <span class="font-medium text-black dark:text-white">5</span>
                        of
                        <span class="font-medium text-black font-black italic dark:text-white">24</span>
                        rooms
                    </p>

                    <div class="flex items-center gap-2">

                        {{-- Previous --}}
                        <button
                            class="inline-flex items-center rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2 text-sm text-gray-400 transition hover:bg-white/[0.06] hover:text-white">
                            Previous
                        </button>

                        {{-- Pages --}}
                        <button
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20">
                            1
                        </button>

                        <button
                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/[0.03] text-sm text-gray-400 transition hover:bg-white/[0.06] hover:text-white">
                            2
                        </button>

                        <button
                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/[0.03] text-sm text-gray-400 transition hover:bg-white/[0.06] hover:text-white">
                            3
                        </button>

                        {{-- Next --}}
                        <button
                            class="inline-flex items-center rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2 text-sm text-gray-400 transition hover:bg-white/[0.06] hover:text-white">
                            Next
                        </button>

                    </div>
                </div>

            </div>
        </div>

        <div class="col-span-12 md:col-span-6">
            <div class="flex h-full flex-col rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-sm transition hover:border-white/20">

                <div class="mb-5 flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-600 dark:text-white">Pending Approvals</h3>
                        <p class="mt-1 text-sm text-gray-500">Bookings awaiting your action.</p>
                    </div>
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-500/10">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>

                <div class="flex flex-col gap-3">

                    <div class="rounded-2xl border border-yellow-500/20 bg-yellow-500/10 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-yellow-400">Room Booking</p>
                                <h4 class="mt-1 font-semibold text-gray-600 dark:text-white">Suite 204 — Ana Reyes</h4>
                                <p class="mt-1 text-sm text-gray-400">May 11, 2026 &bull; 2:00 PM &rarr; May 13</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="rounded-xl bg-green-400 dark:bg-green-500/20 px-3 py-1.5 text-xs font-semibold text-green-800 dark:text-green-300 transition hover:bg-green-500/30">Approve</button>
                                <button class="rounded-xl bg-red-400 dark:bg-red-500/20 px-3 py-1.5 text-xs font-semibold text-red-800 dark:text-red-300 transition hover:bg-red-500/30">Decline</button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-yellow-500/20 bg-yellow-500/10 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-yellow-400">Conference Booking</p>
                                <h4 class="mt-1 font-semibold text-gray-600 dark:text-white">Conference A — IT Dept.</h4>
                                <p class="mt-1 text-sm text-gray-400">May 12, 2026 &bull; 9:00 AM &rarr; 5:00 PM</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="rounded-xl bg-green-400 dark:bg-green-500/20 px-3 py-1.5 text-xs font-semibold text-green-800 dark:text-green-300 transition hover:bg-green-500/30">Approve</button>
                                <button class="rounded-xl bg-red-400 dark:bg-red-500/20 px-3 py-1.5 text-xs font-semibold text-red-800 dark:text-red-300 transition hover:bg-red-500/30">Decline</button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-yellow-500/20 bg-yellow-500/10 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-yellow-400">Room Booking</p>
                                <h4 class="mt-1 font-semibold text-gray-600 dark:text-white">Room 101 — Carlo Tan</h4>
                                <p class="mt-1 text-sm text-gray-400">May 14, 2026 &bull; 1:00 PM &rarr; May 15</p>
                            </div>
                            <div class="flex gap-2">
                                <button class="rounded-xl bg-green-400 dark:bg-green-500/20 px-3 py-1.5 text-xs font-semibold text-green-800 dark:text-green-300 transition hover:bg-green-500/30">Approve</button>
                                <button class="rounded-xl bg-red-400 dark:bg-red-500/20 px-3 py-1.5 text-xs font-semibold text-red-800 dark:text-red-300 transition hover:bg-red-500/30">Decline</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Recent Bookings + Quick Actions --}}
        <div class="col-span-12 md:col-span-6">
            <div class="flex h-full flex-col gap-6">

                {{-- Quick Actions --}}
                <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-sm transition hover:border-white/20">
                    <h3 class="mb-4 font-semibold text-gray-600 dark:text-white">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button class="flex items-center gap-3 rounded-2xl border border-green-500/20 bg-green-500/10 px-4 py-3 text-left transition hover:bg-green-500/20 dark:bg-green-500/20">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-green-400 dark:bg-green-500/20">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-green-300">Check-in Guest</span>
                        </button>
                        <button class="flex items-center gap-3 rounded-2xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-left transition hover:bg-red-500/20  dark:bg-red-500/20">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-400 dark:bg-red-500/20">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-red-300">Check-out Guest</span>
                        </button>
                        <button class="flex items-center gap-3 rounded-2xl border border-blue-500/20 bg-blue-500/10 px-4 py-3 text-left transition hover:bg-blue-500/20">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-500/20">
                                <svg class="h-4 w-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-blue-300">New Booking</span>
                        </button>
                        <button class="flex items-center gap-3 rounded-2xl border border-gray-300 dark:border-white/10 bg-white/[0.04] px-4 py-3 text-left transition hover:bg-gray/[0.07]">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/[0.06]">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-black dark:text-gray-300">View Reports</span>
                        </button>
                    </div>
                </div>

                {{-- Recent Bookings --}}
                <div class="flex-1 rounded-3xl border border-white/10 bg-white/[0.03] p-6 shadow-sm transition hover:border-white/20">
                    <div class="mb-4 flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-600 dark:text-white">Recent Bookings</h3>
                            <p class="mt-1 text-sm text-gray-500">Latest confirmed reservations.</p>
                        </div>
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-500/10">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">

                        <div class="flex items-center justify-between rounded-2xl border border-white/[0.06] bg-white/[0.04] px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-white">Room 101 — Ben Cruz</p>
                                <p class="mt-0.5 text-xs text-gray-500">May 8 &bull; 1:00 PM &rarr; May 9</p>
                            </div>
                            <span class="rounded-full bg-green-500/10 px-2.5 py-1 text-xs font-semibold text-green-400">Confirmed</span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl border border-white/[0.06] bg-white/[0.04] px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-white">Suite 204 — Maria Santos</p>
                                <p class="mt-0.5 text-xs text-gray-500">May 8 &bull; 2:00 PM &rarr; May 10</p>
                            </div>
                            <span class="rounded-full bg-blue-500/10 px-2.5 py-1 text-xs font-semibold text-blue-400">Checked-in</span>
                        </div>

                        <div class="flex items-center justify-between rounded-2xl border border-white/[0.06] bg-white/[0.04] px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-white">Conference A — IT Dept.</p>
                                <p class="mt-0.5 text-xs text-gray-500">May 7 &bull; 9:00 AM &rarr; 5:00 PM</p>
                            </div>
                            <span class="rounded-full bg-gray-500/10 px-2.5 py-1 text-xs font-semibold text-gray-400">Completed</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
    @endrole

    @role('admin')
        <div class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 space-y-6 xl:col-span-7">
            <x-ecommerce.ecommerce-metrics />
            <x-ecommerce.monthly-sale />
            </div>
            <div class="col-span-12 xl:col-span-5">
                <x-ecommerce.monthly-target />
            </div>

            <div class="col-span-12">
            <x-ecommerce.statistics-chart />
            </div>

            <div class="col-span-12 xl:col-span-5">
            {{-- <x-ecommerce.customer-demographic /> --}}
            </div>

            <div class="col-span-12 xl:col-span-7">
            {{-- <x-ecommerce.recent-orders /> --}}
            </div>
        </div>
    @endrole
@endsection

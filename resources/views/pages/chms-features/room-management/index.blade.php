@extends('layouts.authenticated.app')

@section('title', 'Room Management')

@section('content')
    <x-common.page-breadcrumb pageTitle="Room Management" />

    <div class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
        <div class="mx-auto w-full">
            <h3 class="mb-6 font-semibold text-gray-800 text-theme-xl dark:text-white/90 sm:text-2xl">
                Room Management
            </h3>

            <div class="space-y-10">
                <div>
                    <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                        First Floor
                    </h4>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                        <div class="aspect-square rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 p-5 shadow-lg shadow-emerald-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-emerald-500/30 dark:from-emerald-500/20 dark:via-gray-900 dark:to-emerald-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">101</h5>
                                    </div>

                                    <span class="rounded-full bg-emerald-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Available
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-emerald-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:border-emerald-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-emerald-500/20">
                                    <option selected>Available</option>
                                    <option>Not Available</option>
                                    <option>Under Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="aspect-square rounded-2xl border border-rose-200 bg-gradient-to-br from-rose-50 via-white to-rose-100 p-5 shadow-lg shadow-rose-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-rose-500/30 dark:from-rose-500/20 dark:via-gray-900 dark:to-rose-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-rose-600 dark:text-rose-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">102</h5>
                                    </div>

                                    <span class="rounded-full bg-rose-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Not Available
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-rose-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-rose-500 focus:ring-2 focus:ring-rose-200 dark:border-rose-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-rose-500/20">
                                    <option>Available</option>
                                    <option selected>Not Available</option>
                                    <option>Under Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="aspect-square rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 via-white to-amber-100 p-5 shadow-lg shadow-amber-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-amber-500/30 dark:from-amber-500/20 dark:via-gray-900 dark:to-amber-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-amber-600 dark:text-amber-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">103</h5>
                                    </div>

                                    <span class="rounded-full bg-amber-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Maintenance
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-amber-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-200 dark:border-amber-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-amber-500/20">
                                    <option>Available</option>
                                    <option>Not Available</option>
                                    <option selected>Under Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                        Second Floor
                    </h4>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                        <div class="aspect-square rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 p-5 shadow-lg shadow-emerald-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-emerald-500/30 dark:from-emerald-500/20 dark:via-gray-900 dark:to-emerald-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">201</h5>
                                    </div>

                                    <span class="rounded-full bg-emerald-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Available
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-emerald-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:border-emerald-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-emerald-500/20">
                                    <option selected>Available</option>
                                    <option>Not Available</option>
                                    <option>Under Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="aspect-square rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 p-5 shadow-lg shadow-emerald-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-emerald-500/30 dark:from-emerald-500/20 dark:via-gray-900 dark:to-emerald-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">202</h5>
                                    </div>

                                    <span class="rounded-full bg-emerald-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Available
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-emerald-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:border-emerald-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-emerald-500/20">
                                    <option selected>Available</option>
                                    <option>Not Available</option>
                                    <option>Under Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="aspect-square rounded-2xl border border-rose-200 bg-gradient-to-br from-rose-50 via-white to-rose-100 p-5 shadow-lg shadow-rose-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-rose-500/30 dark:from-rose-500/20 dark:via-gray-900 dark:to-rose-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-rose-600 dark:text-rose-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">203</h5>
                                    </div>

                                    <span class="rounded-full bg-rose-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Not Available
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-rose-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-rose-500 focus:ring-2 focus:ring-rose-200 dark:border-rose-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-rose-500/20">
                                    <option>Available</option>
                                    <option selected>Not Available</option>
                                    <option>Under Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="aspect-square rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 via-white to-amber-100 p-5 shadow-lg shadow-amber-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-amber-500/30 dark:from-amber-500/20 dark:via-gray-900 dark:to-amber-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-amber-600 dark:text-amber-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">204</h5>
                                    </div>

                                    <span class="rounded-full bg-amber-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Maintenance
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-amber-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-200 dark:border-amber-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-amber-500/20">
                                    <option>Available</option>
                                    <option>Not Available</option>
                                    <option selected>Under Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="aspect-square rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 p-5 shadow-lg shadow-emerald-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-emerald-500/30 dark:from-emerald-500/20 dark:via-gray-900 dark:to-emerald-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">205</h5>
                                    </div>

                                    <span class="rounded-full bg-emerald-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Available
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-emerald-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:border-emerald-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-emerald-500/20">
                                    <option selected>Available</option>
                                    <option>Not Available</option>
                                    <option>Under Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">
                        Fourth Floor
                    </h4>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                        <div class="aspect-square rounded-2xl border border-rose-200 bg-gradient-to-br from-rose-50 via-white to-rose-100 p-5 shadow-lg shadow-rose-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-rose-500/30 dark:from-rose-500/20 dark:via-gray-900 dark:to-rose-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-rose-600 dark:text-rose-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">401</h5>
                                    </div>

                                    <span class="rounded-full bg-rose-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Not Available
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-rose-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-rose-500 focus:ring-2 focus:ring-rose-200 dark:border-rose-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-rose-500/20">
                                    <option>Available</option>
                                    <option selected>Not Available</option>
                                    <option>Under Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="aspect-square rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-emerald-100 p-5 shadow-lg shadow-emerald-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-emerald-500/30 dark:from-emerald-500/20 dark:via-gray-900 dark:to-emerald-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-emerald-600 dark:text-emerald-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">402</h5>
                                    </div>

                                    <span class="rounded-full bg-emerald-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Available
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-emerald-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:border-emerald-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-emerald-500/20">
                                    <option selected>Available</option>
                                    <option>Not Available</option>
                                    <option>Under Maintenance</option>
                                </select>
                            </div>
                        </div>

                        <div class="aspect-square rounded-2xl border border-amber-200 bg-gradient-to-br from-amber-50 via-white to-amber-100 p-5 shadow-lg shadow-amber-100/70 transition hover:-translate-y-1 hover:shadow-xl dark:border-amber-500/30 dark:from-amber-500/20 dark:via-gray-900 dark:to-amber-900/30 dark:shadow-none">
                            <div class="flex h-full flex-col justify-between">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-amber-600 dark:text-amber-300">Room</p>
                                        <h5 class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">403</h5>
                                    </div>

                                    <span class="rounded-full bg-amber-500 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                        Maintenance
                                    </span>
                                </div>

                                <select class="w-full rounded-xl border border-amber-200 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-200 dark:border-amber-500/30 dark:bg-gray-950 dark:text-white dark:focus:ring-amber-500/20">
                                    <option>Available</option>
                                    <option>Not Available</option>
                                    <option selected>Under Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

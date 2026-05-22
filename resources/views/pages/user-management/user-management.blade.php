@extends('layouts.authenticated.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="User Management" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">

        <div class="mb-5 flex items-center justify-between lg:mb-7">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Users</h3>
            <a href="{{--  --}}"
               class="inline-flex items-center gap-2 rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-amber-50 transition hover:bg-amber-700 active:bg-amber-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 5l0 14" /><path d="M5 12l14 0" />
                </svg>
                Add Staff User
            </a>
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
                                        <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}'s Avatar" class="h-9 w-9 rounded-full object-cover">
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
                                    <a href="{{--  --}}"
                                    class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-blue-700 active:bg-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9" /><path d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <a href="{{--  --}}"
                                    class="inline-flex items-center gap-1 rounded-lg bg-red-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-red-700 active:bg-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
                                        </svg>
                                        Deactivate
                                    </a>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection

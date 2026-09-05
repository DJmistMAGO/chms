<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | CHMS</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/clogo.svg') }}" type="image/x-icon">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                        'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    // When toggling desktop sidebar, ensure mobile menu is closed
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                    // Don't modify isExpanded when toggling mobile menu
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    // Only allow hover effects on desktop when sidebar is collapsed
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark', 'bg-gray-900');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark', 'bg-gray-900');
            }
        })();
    </script>

    {{-- uncomment below this if you want to prevent devtools access --}}
    {{-- @include('components.devtools-protection') --}}

</head>


<body
    x-data="{ 'loaded': true}"
    x-init="$store.sidebar.isExpanded = window.innerWidth >= 1280;
    const checkMobile = () => {
        if (window.innerWidth < 1280) {
            $store.sidebar.setMobileOpen(false);
            $store.sidebar.isExpanded = false;
        } else {
            $store.sidebar.isMobileOpen = false;
            $store.sidebar.isExpanded = true;
        }
    };
    window.addEventListener('resize', checkMobile);">

    <x-common.preloader/>

    <div class="min-h-screen xl:flex">
        @include('layouts.authenticated.backdrop')
        @include('layouts.authenticated.sidebar')

        @php
            $authenticatedUser = auth()->user();
            $needsValidId = $authenticatedUser
                && ! $authenticatedUser->hasAnyRole(['admin', 'staff'])
                && ! $authenticatedUser->hasValidID();
        @endphp

        @if($needsValidId && !request()->routeIs('profile'))
            <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 px-4 backdrop-blur-sm" role="alertdialog" aria-modal="true" aria-labelledby="valid-id-warning-title">
                <div class="w-full max-w-md rounded-2xl border border-amber-200 bg-white p-6 shadow-2xl dark:border-amber-800 dark:bg-gray-900">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.1 14a2 2 0 001.73 3.14h16.16a2 2 0 001.73-3.14l-8.1-14a2 2 0 00-3.42 0z"/></svg>
                        </div>
                        <h2 id="valid-id-warning-title" class="text-lg font-semibold text-gray-900 dark:text-white">Valid ID required</h2>
                    </div>
                    <p class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300">Please upload a valid government-issued or any other valid ID before you continue. We require an ID to verify your identity, protect guest accounts, and help prevent fraudulent reservations.</p>
                    <p class="mt-3 rounded-lg bg-gray-50 px-3 py-2.5 text-xs leading-5 text-gray-500 dark:bg-gray-800/60 dark:text-gray-400"><span class="font-semibold text-gray-700 dark:text-gray-300">Data privacy:</span> Your ID will be stored securely and used only for identity verification and reservation processing. Access is limited to authorized hotel staff.</p>
                    <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">Reservations remain unavailable until an ID is uploaded.</p>
                    <a href="{{ route('profile') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600">Upload valid ID</a>
                </div>
            </div>
        @endif

        <div class="flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">
            <!-- app header start -->
            @include('layouts.authenticated.app-header')
            <!-- app header end -->
            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                @yield('content')
            </div>
        </div>

    </div>

    @include('components.common.unsaved-changes')

</body>

@stack('scripts')

</html>

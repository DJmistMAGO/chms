@extends('layouts.authenticated.fullscreen-layout')

@section('content')
@php
    $currentYear = date('Y');
@endphp
<style>
    @keyframes falling {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }
    .falling-animation {
        animation: falling 3s ease-in infinite;
    }
</style>
    <div class="relative flex flex-col items-center justify-center min-h-screen p-6 overflow-hidden z-1">
        <x-common.common-grid-shape />

        <!-- Falling Room Animation -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-20 h-20 mb-8">
            <svg class="falling-animation w-full h-full text-yellow-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 3h18v18H3V3m2 2v14h14V5H5m2 2h10v3H7V7m0 5h10v3H7v-3m0 5h10v2H7v-2z"/>
            </svg>
        </div>

        <div class="mx-auto w-full max-w-[242px] text-center sm:max-w-[472px]">
            <h1 class="mb-8 font-bold text-gray-800 text-title-md dark:text-white/90 xl:text-title-xl">
                ROOM NOT FOUND
            </h1>

            <img src="/images/error/404.svg" alt="404" class="dark:hidden" />
            <img src="/images/error/404-dark.svg" alt="404" class="hidden dark:block" />

            <p class="mt-10 mb-6 text-base text-gray-700 dark:text-gray-400 sm:text-lg">
                Oops! The room or floor you're looking for seems to have fallen off our building!
            </p>

            <a href="/"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                Back to Lobby
            </a>
        </div>
        <p class="absolute text-sm text-center text-gray-500 -translate-x-1/2 bottom-6 left-1/2 dark:text-gray-400">
            &copy; {{ $currentYear }} - CHMS. All rights reserved.
        </p>
    </div>
@endsection

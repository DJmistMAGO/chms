@extends('layouts.authenticated.fullscreen-layout')

@section('content')
@php $currentYear = date('Y'); @endphp

<style>
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes dust-float {
        0%, 100% { opacity: 0.18; }
        50%       { opacity: 0.08; }
    }

    @media (prefers-reduced-motion: reduce) {
        .e404-fu1, .e404-fu2, .e404-fu3, .e404-fu4 { animation: none !important; }
    }
    .e404-fu1 { animation: fade-up .4s ease both; }
    .e404-fu2 { animation: fade-up .4s .08s ease both; }
    .e404-fu3 { animation: fade-up .4s .16s ease both; }
    .e404-fu4 { animation: fade-up .4s .24s ease both; }

    .dust {
        background-image:
            radial-gradient(circle, rgba(255,255,255,0.18) 1px, transparent 1px),
            radial-gradient(circle, rgba(255,255,255,0.10) 1px, transparent 1px),
            radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
        background-size: 120px 120px, 80px 80px, 200px 200px;
        background-position: 0 0, 40px 60px, 90px 30px;
    }
</style>

    <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-6 py-14 text-center"
        style="background: radial-gradient(ellipse at center, #f5a623 0%, #d4780a 50%, #b85e00 100%);">

        <div class="dust absolute inset-0 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col items-center">

            <h1 class="e404-fu1 font-black leading-none tracking-tighter text-white"
                style="font-size: clamp(120px, 20vw, 200px);">
                403
            </h1>

            <p class="e404-fu2 mt-2 mb-8 text-xs font-bold uppercase tracking-widest text-white">
                We are sorry, but you do not have permission to access this page
            </p>

            <div class="e404-fu3 flex flex-wrap items-center justify-center gap-4 mb-10">
                <a href="/"
                class="inline-flex items-center justify-center gap-2 rounded-full border-2 border-white bg-white/95 px-8 py-3 text-xs font-bold uppercase tracking-widest text-amber-700 transition-all duration-200 hover:border-amber-600 hover:bg-amber-600 hover:scale-105 hover:shadow-lg hover:text-white focus:outline-none focus:ring-2 focus:ring-amber-300">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Go Home
                </a>
            </div>
        </div>
        <p class="absolute bottom-4 left-1/2 -translate-x-1/2 text-center text-xs text-white/50">
            &copy; {{ $currentYear }} Caree Hotel Management System
        </p>
    </div>
@endsection

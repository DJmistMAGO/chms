<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Caree Hotel' }} | Caree Hotel</title>

    <link rel="preload" as="image" href="{{ asset('assets/images/ch2.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/images/clogo.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Cormorant Garamond"', 'serif'],
                        sans: ['"DM Sans"', 'sans-serif']
                    },
                    colors: {
                        primary: '#c9960c',
                        secondary: '#b8860b',
                        accent: '#f6d98c',
                        dark: '#1d120d',
                        ink: '#21160e',
                        ivory: '#f8f2ea',
                        stone: {
                            50: '#fffdf9',
                            100: '#f9f3ea',
                            200: '#e8dcc3',
                            300: '#d7c7a2',
                            400: '#b99b5a',
                            500: '#9e7a31',
                            600: '#72581d',
                            700: '#423214',
                            800: '#241c12',
                            900: '#120d09'
                        },
                        gold: {
                            50: '#fff9ee',
                            100: '#fef0d1',
                            200: '#f7d884',
                            300: '#efc75d',
                            400: '#dca81c',
                            500: '#c9960c',
                            600: '#b8860b',
                            700: '#8b6508',
                            900: '#3b2a0f'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background: #f8f2ea;
            color: #21160e;
        }

        .nav-solid {
            background-color: rgba(255, 252, 248, 0.92);
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(32, 20, 10, 0.12);
            border-bottom: 1px solid rgba(201, 150, 12, 0.18);
        }

        .nav-solid .nav-links a:not(.rounded-full) { color: #21160e; }
        .nav-solid #bar1, .nav-solid #bar2, .nav-solid #bar3 { background-color: #21160e; }
        .nav-solid .rounded-full { border-color: rgba(33,22,14,0.3); color: #21160e; }

        .hero-slide {
            opacity: 0;
            transform: scale(1.05);
            transition: opacity 1200ms cubic-bezier(0.4, 0, 0.2, 1), transform 1800ms ease-out;
        }

        .hero-slide.is-active {
            opacity: 1;
            transform: scale(1);
        }

        .hero-progress-bar {
            width: 0%;
            transition: width 5500ms linear;
        }

        .is-active .hero-progress-bar {
            width: 100%;
        }

        .glass-panel {
            background: rgba(27, 18, 13, 0.52);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 25px 60px rgba(20, 13, 8, 0.45);
        }

        .warm-card {
            background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(249,243,234,0.98));
            border: 1px solid rgba(201, 150, 12, 0.12);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const bar1 = document.getElementById('bar1');
            const bar2 = document.getElementById('bar2');
            const bar3 = document.getElementById('bar3');

            function closeMobileMenu() {
                mobileMenu.classList.add('hidden');
                bar1.classList.remove('rotate-45', 'translate-y-1.5');
                bar2.classList.remove('opacity-0');
                bar3.classList.remove('-rotate-45', '-translate-y-1.5');
                menuToggle.setAttribute('aria-expanded', 'false');
            }

            menuToggle.addEventListener('click', function() {
                const isOpen = !mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                bar1.classList.toggle('rotate-45');
                bar1.classList.toggle('translate-y-1.5');
                bar2.classList.toggle('opacity-0');
                bar3.classList.toggle('-rotate-45');
                bar3.classList.toggle('-translate-y-1.5');
                menuToggle.setAttribute('aria-expanded', String(!isOpen));
            });

            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', closeMobileMenu);
            });

            const nav = document.getElementById('main-nav');
            const hero = document.getElementById('home');
            const navObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    nav.classList.toggle('nav-solid', !entry.isIntersecting || entry.intersectionRatio < 0.92);
                });
            }, { threshold: [0, 0.92] });
            navObserver.observe(hero);

            // Dynamic Carousel Scripting
            const slides = Array.from(document.querySelectorAll('.hero-slide'));
            const titleEl = document.getElementById('hero-room-title');
            const priceEl = document.getElementById('hero-room-price');
            const dots = Array.from(document.querySelectorAll('.hero-dot'));
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            let current = 0;
            let timer = null;

            function goTo(index) {
                slides[current].classList.remove('is-active');
                dots[current].classList.remove('is-active');

                const currentBar = dots[current].querySelector('.hero-progress-bar');
                if (currentBar) {
                    currentBar.style.transition = 'none';
                    currentBar.style.width = '0%';
                }

                current = (index + slides.length) % slides.length;

                slides[current].classList.add('is-active');
                dots[current].classList.add('is-active');

                const nextBar = dots[current].querySelector('.hero-progress-bar');
                if (nextBar && !reduceMotion) {
                    setTimeout(() => {
                        nextBar.style.transition = 'width 5500ms linear';
                        nextBar.style.width = '100%';
                    }, 50);
                }

                // Smoothly fade captions out and in
                const captionBox = document.getElementById('hero-caption-box');
                captionBox.style.opacity = '0';
                captionBox.style.transform = 'translateY(6px)';

                window.setTimeout(() => {
                    titleEl.textContent = slides[current].dataset.title;
                    priceEl.textContent = slides[current].dataset.price;
                    captionBox.style.opacity = '1';
                    captionBox.style.transform = 'translateY(0px)';
                }, 300);
            }

            function start() {
                if (reduceMotion) return;
                const nextBar = dots[current].querySelector('.hero-progress-bar');
                if (nextBar) {
                    nextBar.style.transition = 'width 5500ms linear';
                    nextBar.style.width = '100%';
                }
                timer = window.setInterval(() => goTo(current + 1), 5500);
            }

            function stop() {
                if (timer) window.clearInterval(timer);
                const currentBar = dots[current].querySelector('.hero-progress-bar');
                if (currentBar) {
                    currentBar.style.transition = 'none';
                    currentBar.style.width = '0%';
                }
            }

            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => { stop(); goTo(i); start(); });
            });

            document.getElementById('hero-prev').addEventListener('click', () => {
                stop();
                goTo(current - 1);
                start();
            });

            document.getElementById('hero-next').addEventListener('click', () => {
                stop();
                goTo(current + 1);
                start();
            });

            const heroCarousel = document.getElementById('hero-carousel');
            heroCarousel.addEventListener('mouseenter', stop);
            heroCarousel.addEventListener('mouseleave', start);

            titleEl.textContent = slides[0].dataset.title;
            priceEl.textContent = slides[0].dataset.price;
            start();
        });
    </script>

    @include('components.devtools-protection')
</head>

<body class="bg-ivory text-ink font-sans selection:bg-gold-400 selection:text-ink">

    <nav id="main-nav" class="fixed w-full top-0 z-50 px-6 md:px-16 py-4 transition-all duration-500">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="/" class="flex items-center gap-2 group">
                <img src="{{ asset('assets/images/chlogo.png') }}" class="w-16 transition-transform duration-300 group-hover:scale-105" alt="Caree Hotel Logo">
            </a>

            <div class="hidden md:flex space-x-10 font-sans text-xs uppercase tracking-[0.18em] text-white/90 items-center nav-links font-medium">
                <a href="#home" class="relative pb-1 hover:text-gold-300 transition-colors">Home</a>
                <a href="#rooms" class="relative pb-1 hover:text-gold-300 transition-colors">Rooms</a>
                <a href="#about-us" class="relative pb-1 hover:text-gold-300 transition-colors">About Us</a>
                <a href="{{ route('login') }}"
                    class="border border-white/40 rounded-full px-6 py-2.5 hover:border-gold-300 hover:text-gold-300 transition-all duration-300 backdrop-blur-sm">
                    Log In
                </a>
            </div>

            <button id="menu-toggle"
                class="md:hidden flex flex-col justify-center items-center w-8 h-8 space-y-1.5 focus:outline-none"
                aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-menu">
                <span id="bar1" class="block w-6 h-0.5 bg-white transition-all duration-300"></span>
                <span id="bar2" class="block w-6 h-0.5 bg-white transition-all duration-300"></span>
                <span id="bar3" class="block w-6 h-0.5 bg-white transition-all duration-300"></span>
            </button>
        </div>

        <div id="mobile-menu"
            class="md:hidden hidden flex-col space-y-4 pt-6 pb-6 font-sans text-ink border-t border-ink/10 mt-3 bg-ivory/95 backdrop-blur-xl -mx-6 px-8 rounded-b-2xl shadow-xl">
            <a href="#home" class="block text-sm uppercase tracking-wider font-medium hover:text-gold-600">Home</a>
            <a href="#rooms" class="block text-sm uppercase tracking-wider font-medium hover:text-gold-600">Rooms</a>
            <a href="#about-us" class="block text-sm uppercase tracking-wider font-medium hover:text-gold-600">About Us</a>
            <a href="{{ route('login') }}" class="block text-sm uppercase tracking-wider font-medium text-gold-600">Log In</a>
        </div>
    </nav>

    <section class="relative min-h-screen flex items-center overflow-hidden bg-cover bg-center scroll-mt-24"
    id="home"
    style="background-image: linear-gradient(135deg, rgba(18,13,9,0.82), rgba(44,32,16,0.72), rgba(201,150,12,0.38)), url('{{ asset('assets/images/ch2.png') }}')">

    <div class="absolute inset-0 z-[5] pointer-events-none bg-[radial-gradient(circle_at_top_left,_rgba(255,223,130,0.25),transparent_30%),linear-gradient(90deg,_rgba(248,242,234,0.08)_0%,_rgba(248,242,234,0.15)_25%,_transparent_60%)]"></div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-6 md:px-16 py-32 md:py-20 flex flex-col md:flex-row items-center gap-14 md:gap-12 justify-between">

        <div class="max-w-xl">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/8 border border-gold-300/40 mb-6 backdrop-blur-sm shadow-[0_0_30px_rgba(201,150,12,0.15)]">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-300 animate-pulse"></span>
                <span class="text-gold-200 text-[11px] tracking-[0.22em] uppercase font-medium">Bulan, Sorsogon</span>
            </div>

            <h1 class="font-display text-5xl md:text-7xl font-semibold leading-[0.92] tracking-[-0.03em] text-white">
                Find your <span class="italic text-gold-300 font-normal">peace</span> here
            </h1>

            <p class="mt-6 text-base md:text-lg leading-relaxed text-white/75 max-w-md font-light">
                A quiet retreat where modern luxury meets Bulan's slower pace — tailored by the room, the view, and the moments that matter.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row gap-4">
                <button
                    class="w-full sm:w-auto sm:min-w-[210px] bg-gradient-to-r from-gold-400 to-gold-300 hover:brightness-110 text-ink px-8 py-4 rounded-full font-medium transition-all duration-300 text-center shadow-[0_12px_28px_rgba(201,150,12,0.35)] hover:-translate-y-0.5"
                    onclick="document.getElementById('rooms').scrollIntoView({ behavior: 'smooth' })">
                    Explore Rooms
                </button>
            </div>
        </div>

        <div class="w-full max-w-[320px] md:max-w-[380px] lg:max-w-[420px] shrink-0">
            <div class="relative flex items-center gap-3">
                <button id="hero-prev" type="button"
                    class="shrink-0 w-10 h-10 rounded-full border border-white/30 bg-ink/45 text-white backdrop-blur-md flex items-center justify-center hover:bg-gold-500 hover:text-ink hover:border-gold-300 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-gold-300"
                    aria-label="Previous room">
                    <i class="fas fa-chevron-left text-xs" aria-hidden="true"></i>
                </button>

                <div id="hero-carousel" class="relative min-w-0 flex-1 rounded-[2rem] p-3 glass-panel shadow-[0_25px_60px_rgba(0,0,0,0.5)]">
                <div class="relative rounded-[1.5rem] overflow-hidden aspect-[3/4]">

                    <div class="hero-slide is-active absolute inset-0 bg-cover bg-center"
                        data-title="Standard Suite"
                        data-price="₱1,500 / night"
                        style="background-image: linear-gradient(to top, rgba(17,12,10,0.9) 0%, rgba(17,12,10,0.2) 60%, rgba(17,12,10,0) 100%), url('{{ asset('assets/images/sRoom.png') }}')"></div>

                    <div class="hero-slide absolute inset-0 bg-cover bg-center"
                        data-title="Standard Premium"
                        data-price="₱1,900 / night"
                        style="background-image: linear-gradient(to top, rgba(17,12,10,0.9) 0%, rgba(17,12,10,0.2) 60%, rgba(17,12,10,0) 100%), url('{{ asset('assets/images/pRoom.png') }}')"></div>

                    <div class="hero-slide absolute inset-0 bg-cover bg-center"
                        data-title="Family Residence"
                        data-price="₱2,700 / night"
                        style="background-image: linear-gradient(to top, rgba(17,12,10,0.9) 0%, rgba(17,12,10,0.2) 60%, rgba(17,12,10,0) 100%), url('{{ asset('assets/images/fRoom.png') }}')"></div>

                    <div class="absolute top-4 right-4 z-10">
                        <span class="bg-[#1d120d]/65 backdrop-blur-md border border-gold-300/25 text-gold-200 text-[10px] font-semibold uppercase tracking-[0.22em] px-3 py-1.5 rounded-full">
                            Featured Accommodations
                        </span>
                    </div>

                    <div class="absolute bottom-6 left-6 right-6 z-10">
                        <div id="hero-caption-box" class="transition-all duration-300" aria-live="polite">
                            <h4 id="hero-room-title" class="text-white font-display text-3xl font-medium tracking-wide">Standard Suite</h4>
                            <p id="hero-room-price" class="text-gold-300 font-sans text-sm font-medium mt-1">₱1,500 / night</p>
                        </div>

                        <div class="flex gap-2.5 mt-5">
                            <button type="button" class="hero-dot is-active relative h-1 flex-1 rounded-full bg-white/20 overflow-hidden" aria-label="Slide 1">
                                <div class="hero-progress-bar h-full bg-gold-300 rounded-full"></div>
                            </button>
                            <button type="button" class="hero-dot relative h-1 flex-1 rounded-full bg-white/20 overflow-hidden" aria-label="Slide 2">
                                <div class="hero-progress-bar h-full bg-gold-300 rounded-full"></div>
                            </button>
                            <button type="button" class="hero-dot relative h-1 flex-1 rounded-full bg-white/20 overflow-hidden" aria-label="Slide 3">
                                <div class="hero-progress-bar h-full bg-gold-300 rounded-full"></div>
                            </button>
                        </div>
                    </div>

                </div>
                </div>

                <button id="hero-next" type="button"
                    class="shrink-0 w-10 h-10 rounded-full border border-white/30 bg-ink/45 text-white backdrop-blur-md flex items-center justify-center hover:bg-gold-500 hover:text-ink hover:border-gold-300 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-gold-300"
                    aria-label="Next room">
                    <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</section>

    <section class="py-32 px-6 md:px-16 bg-[#f6efe7] scroll-mt-24" id="rooms">
        <div class="max-w-7xl mx-auto">
            <div class="mb-20 max-w-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-10 h-px bg-gold-500"></span>
                    <span class="text-gold-600 font-sans text-xs uppercase tracking-[0.2em] font-semibold">Accommodations</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-display font-semibold text-ink leading-tight">
                    Explore &amp; book your sanctuary
                </h2>
                <p class="mt-4 text-[#5d4c3d] text-base leading-relaxed max-w-xl font-light">
                    Every detail designed with intent. Rates are curated by space, vista, and bespoke features so you pay only for what you desire.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">

                <div class="group warm-card rounded-3xl overflow-hidden shadow-[0_20px_50px_rgba(35,24,14,0.08)] hover:shadow-[0_25px_60px_rgba(35,24,14,0.14)] hover:-translate-y-1.5 transition-all duration-500 flex flex-col">
                    <div class="relative overflow-hidden h-64 bg-gold-50">
                        <img src="{{ asset('assets/images/sRoom.png') }}" alt="Standard Room" loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <span class="absolute top-5 right-5 bg-ink/80 backdrop-blur-md text-ivory text-[11px] font-medium px-4 py-1.5 rounded-full tracking-wider uppercase">
                            Standard
                        </span>
                    </div>
                    <div class="p-8 flex flex-col flex-1">
                        <h3 class="text-2xl font-display font-normal text-ink mb-6">Standard Room</h3>

                        <ul class="space-y-4 mb-8 text-sm text-neutral-600">
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-50 flex items-center justify-center text-gold-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4h4M16 4h4v4M20 16v4h-4M8 20H4v-4"/>
                                    </svg>
                                </span>
                                <span class="font-light">20 sqm space</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-50 flex items-center justify-center text-gold-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 18v-6a2 2 0 012-2h14a2 2 0 012 2v6M3 18h18M3 18v2M21 18v2M7 10V7a2 2 0 012-2h6a2 2 0 012 2v3"/>
                                    </svg>
                                </span>
                                <span class="font-light">1 Queen Bed</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-50 flex items-center justify-center text-gold-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                <span class="font-light">Up to 2 guests</span>
                            </li>
                        </ul>

                        <div class="pt-6 border-t border-ink/5 flex items-center justify-between mt-auto">
                            <div>
                                <p class="text-[11px] uppercase tracking-wider text-neutral-400 font-medium">Starting from</p>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span class="text-3xl font-display font-light text-ink">₱1,500</span>
                                    <span class="text-neutral-400 text-xs">/ night</span>
                                </div>
                            </div>
                            <a href="{{ route('customize.booking', ['roomType' => 'standard']) }}"
                                class="inline-flex items-center gap-2 bg-ink hover:bg-gold-500 text-white font-medium text-xs uppercase tracking-wider px-5 py-3 rounded-full transition-all duration-300">
                                Reserve
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="group bg-white rounded-3xl overflow-hidden shadow-[0_20px_50px_rgba(15,26,34,0.05)] hover:shadow-[0_25px_60px_rgba(15,26,34,0.12)] hover:-translate-y-1.5 transition-all duration-500 flex flex-col border border-gold-400/20 relative">
                    <div class="relative overflow-hidden h-64 bg-gold-50">
                        <img src="{{ asset('assets/images/pRoom.png') }}" alt="Standard Premium Room" loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <span class="absolute top-5 right-5 bg-gold-500 text-ink text-[11px] font-semibold px-4 py-1.5 rounded-full tracking-wider uppercase shadow-sm">
                            Premium
                        </span>
                    </div>
                    <div class="p-8 flex flex-col flex-1">
                        <h3 class="text-2xl font-display font-normal text-ink mb-6">Standard Premium</h3>

                        <ul class="space-y-4 mb-8 text-sm text-neutral-600">
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-50 flex items-center justify-center text-gold-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4h4M16 4h4v4M20 16v4h-4M8 20H4v-4"/>
                                    </svg>
                                </span>
                                <span class="font-light">25 sqm space</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-50 flex items-center justify-center text-gold-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 18v-6a2 2 0 012-2h14a2 2 0 012 2v6M3 18h18M3 18v2M21 18v2M7 10V7a2 2 0 012-2h6a2 2 0 012 2v3"/>
                                    </svg>
                                </span>
                                <span class="font-light">1 Queen Bed</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-50 flex items-center justify-center text-gold-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                <span class="font-light">Up to 2 guests</span>
                            </li>
                        </ul>

                        <div class="pt-6 border-t border-ink/5 flex items-center justify-between mt-auto">
                            <div>
                                <p class="text-[11px] uppercase tracking-wider text-neutral-400 font-medium">Starting from</p>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span class="text-3xl font-display font-light text-ink">₱1,900</span>
                                    <span class="text-neutral-400 text-xs">/ night</span>
                                </div>
                            </div>
                            <a href="{{ route('customize.booking', ['roomType' => 'standard-premium']) }}"
                                class="inline-flex items-center gap-2 bg-ink hover:bg-gold-500 text-white font-medium text-xs uppercase tracking-wider px-5 py-3 rounded-full transition-all duration-300">
                                Reserve
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="group bg-white rounded-3xl overflow-hidden shadow-[0_20px_50px_rgba(15,26,34,0.05)] hover:shadow-[0_25px_60px_rgba(15,26,34,0.12)] hover:-translate-y-1.5 transition-all duration-500 flex flex-col border border-ink/5">
                    <div class="relative overflow-hidden h-64 bg-gold-50">
                        <img src="{{ asset('assets/images/fRoom.png') }}" alt="Family Room" loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <span class="absolute top-5 right-5 bg-ink/80 backdrop-blur-md text-ivory text-[11px] font-medium px-4 py-1.5 rounded-full tracking-wider uppercase">
                            Family Suite
                        </span>
                    </div>
                    <div class="p-8 flex flex-col flex-1">
                        <h3 class="text-2xl font-display font-normal text-ink mb-6">Family Room</h3>

                        <ul class="space-y-4 mb-8 text-sm text-neutral-600">
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-50 flex items-center justify-center text-gold-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4h4M16 4h4v4M20 16v4h-4M8 20H4v-4"/>
                                    </svg>
                                </span>
                                <span class="font-light">30 sqm space</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-50 flex items-center justify-center text-gold-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 18v-6a2 2 0 012-2h14a2 2 0 012 2v6M3 18h18M3 18v2M21 18v2M7 10V7a2 2 0 012-2h6a2 2 0 012 2v3"/>
                                    </svg>
                                </span>
                                <span class="font-light">2 Queen Beds</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-gold-50 flex items-center justify-center text-gold-600 shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </span>
                                <span class="font-light">Up to 4 guests</span>
                            </li>
                        </ul>

                        <div class="pt-6 border-t border-ink/5 flex items-center justify-between mt-auto">
                            <div>
                                <p class="text-[11px] uppercase tracking-wider text-neutral-400 font-medium">Starting from</p>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span class="text-3xl font-display font-light text-ink">₱2,700</span>
                                    <span class="text-neutral-400 text-xs">/ night</span>
                                </div>
                            </div>
                            <a href="{{ route('customize.booking', ['roomType' => 'family']) }}"
                                class="inline-flex items-center gap-2 bg-ink hover:bg-gold-500 text-white font-medium text-xs uppercase tracking-wider px-5 py-3 rounded-full transition-all duration-300">
                                Reserve
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-32 px-6 md:px-16 bg-[#fffdf9] scroll-mt-24 border-t border-b border-[#e7d8b5]" id="about-us">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16">

            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-10 h-px bg-gold-500"></span>
                    <span class="text-gold-600 font-sans text-xs uppercase tracking-[0.2em] font-semibold">Why Caree</span>
                </div>
                <h2 class="text-3xl md:text-5xl font-display font-light text-ink mb-12 leading-tight">
                    What makes a stay here extraordinary
                </h2>

                <div class="space-y-8">
                    <div class="flex gap-6 items-start">
                        <div class="bg-gold-50 border border-gold-200/50 w-14 h-14 flex items-center justify-center rounded-2xl text-gold-600 flex-shrink-0">
                            <i class="fas fa-star text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-display text-xl text-ink font-normal">Micro pricing</h3>
                            <p class="text-neutral-600 mt-2 leading-relaxed text-sm font-light">
                                Room rates are broken down by view, layout, and ambiance — you choose what matters and skip what doesn't.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-6 items-start">
                        <div class="bg-gold-50 border border-gold-200/50 w-14 h-14 flex items-center justify-center rounded-2xl text-gold-600 flex-shrink-0">
                            <i class="fas fa-shield-alt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-display text-xl text-ink font-normal">Verified bookings</h3>
                            <p class="text-neutral-600 mt-2 leading-relaxed text-sm font-light">
                                Two-step identity verification keeps every reservation protected and completely seamless.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-6 items-start">
                        <div class="bg-gold-50 border border-gold-200/50 w-14 h-14 flex items-center justify-center rounded-2xl text-gold-600 flex-shrink-0">
                            <i class="fas fa-bed text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-display text-xl text-ink font-normal">Considered comfort</h3>
                            <p class="text-neutral-600 mt-2 leading-relaxed text-sm font-light">
                                High-speed internet, premium bedding, and a tranquil atmosphere situated right in the heart of Sorsogon.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 w-full">
                <div class="relative rounded-3xl overflow-hidden shadow-[0_30px_70px_rgba(15,26,34,0.1)] p-2 bg-ivory border border-ink/5">
                    <img src="{{ asset('assets/images/ch1.png') }}" loading="lazy"
                        class="rounded-2xl w-full max-h-[70vh] object-cover">
                </div>
            </div>
        </div>
    </section>

    <section class="min-h-screen bg-cover bg-center flex items-center px-6 md:px-16 py-24 text-white relative"
        style="background-image: linear-gradient(135deg, rgba(18,13,9,0.88), rgba(45,34,17,0.82), rgba(201,150,12,0.35)), url('{{ asset('assets/images/ch1.png') }}')">

        <div class="max-w-7xl mx-auto w-full">
            <div class="max-w-3xl">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-px bg-gold-400"></span>
                    <span class="text-gold-400 font-sans text-xs uppercase tracking-[0.2em] font-semibold">Our story</span>
                </div>

                <h2 class="text-4xl md:text-6xl font-display font-light leading-tight">
                    A sanctuary of serenity in
                    <span class="text-gold-200 italic font-normal">Bulan, Sorsogon</span>
                </h2>

                <p class="mt-8 text-base md:text-lg leading-relaxed text-white/80 max-w-xl font-light">
                    Founded in 2016, Caree Hotel was established to bring international standards of hospitality to the heart of Bulan — a quiet, refined sanctuary where every guest can rest and unwind.
                </p>

                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-white/10 pt-8">
                    <div>
                        <h3 class="text-2xl font-display text-gold-400 font-light">2016</h3>
                        <p class="text-xs text-white/70 mt-2 leading-relaxed uppercase tracking-wider">
                            Established to deliver quality hospitality and comfort.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-2xl font-display text-gold-400 font-light">Mission</h3>
                        <p class="text-xs text-white/70 mt-2 leading-relaxed uppercase tracking-wider">
                            Promoting excellence in the local hospitality industry.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-2xl font-display text-gold-400 font-light">Innovation</h3>
                        <p class="text-xs text-white/70 mt-2 leading-relaxed uppercase tracking-wider">
                            Micro pricing lets guests shape their own stay.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-ink text-white px-6 md:px-16 py-12 border-t border-white/10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <a href="/" class="opacity-90 hover:opacity-100 transition-opacity">
                <img src="{{ asset('assets/images/chlogo.png') }}" class="w-24" alt="Caree Hotel Logo">
            </a>

            <p class="text-xs text-white/50 tracking-wide">&copy; 2026 Caree Hotel. All rights reserved.</p>

            <div class="space-x-6 text-xs uppercase tracking-widest text-white/70">
                <a href="{{ route('privacy.policy') }}" class="hover:text-gold-400 transition-colors">Privacy Policy</a>
                <a href="{{ route('terms.of.service') }}" class="hover:text-gold-400 transition-colors">Terms of Service</a>
            </div>
        </div>
    </footer>

</body>

</html>

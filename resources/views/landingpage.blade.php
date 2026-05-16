<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Caree Hotel' }} | Caree Hotel</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/images/clogo.svg') }}">


    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FFD000',
                        secondary: '#F9AE36',
                        light: '#FFE1A4',
                        dark: '#000000',

                        neutral: {
                            50: '#FFFFFF',
                            100: '#F5F5F5',
                            200: '#E5E7EB',
                            300: '#D1D5DB',
                            400: '#9CA3AF',
                            500: '#6B7280',
                            600: '#4B5563',
                            700: '#374151',
                            800: '#1F2937',
                            900: '#111827'
                        },

                        accent: {
                            blue: '#2563EB',
                            teal: '#14B8A6',
                            navy: '#1E3A8A'
                        },

                        success: '#22C55E',
                        warning: '#F59E0B',
                        error: '#EF4444',
                        info: '#3B82F6'
                    }
                }
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const bar1 = document.getElementById('bar1');
            const bar2 = document.getElementById('bar2');
            const bar3 = document.getElementById('bar3');

            menuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                bar1.classList.toggle('rotate-45');
                bar1.classList.toggle('translate-y-1.5');
                bar2.classList.toggle('opacity-0');
                bar3.classList.toggle('-rotate-45');
                bar3.classList.toggle('-translate-y-1.5');
            });
        });
    </script>

    <style>
        /* body { font-family: 'DM Sans', sans-serif; } */
        /* h1, h2, h3, .font-display { font-family: 'Cormorant Garamond', serif; } */
    </style>
</head>

<body class="bg-white text-black font-sans">

    <nav class="fixed w-full top-0 bg-white shadow z-50 px-6 md:px-16 py-4">
        <div class="flex justify-between items-center">
            <a href="/">
                <img src="{{ asset('assets/images/chlogo.png') }}" class="w-20">
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex space-x-6 font-medium items-center">
                <a href="#" class="hover:text-primary">HOME</a>
                <a href="#" class="hover:text-primary">ROOMS</a>
                <a href="{{ route('login') }}" class="hover:text-primary ps-5">LOGIN</a>
            </div>

            {{-- Hamburger Button (mobile only) --}}
            <button id="menu-toggle"
                class="md:hidden flex flex-col justify-center items-center w-8 h-8 space-y-1.5 focus:outline-none"
                aria-label="Toggle menu">
                <span id="bar1" class="block w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
                <span id="bar2" class="block w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
                <span id="bar3" class="block w-6 h-0.5 bg-gray-800 transition-all duration-300"></span>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu"
            class="md:hidden hidden flex-col space-y-4 pt-4 pb-2 font-medium border-t border-gray-100 mt-3">
            <a href="#" class="block hover:text-primary">HOME</a>
            <a href="#" class="block hover:text-primary">ROOMS</a>
            <a href="{{ route('login') }}" class="block hover:text-primary">LOGIN</a>
        </div>
    </nav>




    <section class="h-screen bg-cover bg-center flex items-center px-6 md:px-16 text-white"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.716)), url('{{ asset('assets/images/ch2.png') }}')">

        <div class="max-w-xl">
            <h1 class="text-4xl md:text-6xl font-bold mt-10">
                FIND YOUR <span class="text-primary">PEACE</span> HERE.
            </h1>

            <p class="mt-4 text-sm md:text-lg leading-relaxed ">
                Experience the perfect blend of modern comfort and serene ambiance.
                Our micro-pricing ensures you only pay for the views and features you love.
            </p>

            <div class="mt-6 flex flex-col md:flex-row md:items-left md:justify-left gap-3">
                <button
                    class="w-full md:w-auto md:min-w-[180px] bg-primary text-white px-6 py-3 rounded-full font-bold hover:bg-secondary hover:scale-105 hover:text-black transition text-center">
                    Start Reservation
                </button>
                <button
                    class="w-full md:w-auto md:min-w-[180px] border border-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-black hover:scale-105 transition text-center">
                    Explore Rooms
                </button>
            </div>
        </div>
    </section>

    {{-- Explore & Book Rooms Section --}}
    <section class="py-20 px-6 md:px-16 bg-white">

        {{-- Section Header --}}
        <div class="text-center mb-14">
            <span
                class="inline-block bg-yellow-400 text-yellow-900 text-xs font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full mb-4">
                Our Accommodations
            </span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                Explore & Book Your Room
            </h2>
            <p class="mt-4 text-gray-500 text-lg max-w-xl mx-auto">
                Discover our carefully curated rooms — each designed for comfort, style, and a stay you'll remember.
            </p>
        </div>

        {{-- Room Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            {{-- Standard Room --}}
            <div
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                <div class="relative overflow-hidden h-56 bg-yellow-50">
                    <img src="{{ asset('assets/images/sRoom.png') }}" alt="Standard Room"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span
                        class="absolute top-4 left-4 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                        Standard
                    </span>
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Standard Room</h3>
                    <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                        A clean, comfortable room with all essential amenities — perfect for solo travelers or couples
                        on a budget.
                    </p>
                    <ul class="flex flex-wrap gap-2 mb-5 text-xs text-gray-500">
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> Free Wi-Fi
                        </li>
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> Air Conditioning
                        </li>
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> 1 Queen Bed
                        </li>
                    </ul>
                    <div class="flex items-center justify-between mt-auto">
                        <div>
                            <span class="text-2xl font-bold text-gray-900">₱1,500</span>
                            <span class="text-gray-400 text-sm"> / night</span>
                        </div>
                        <a href=""
                            class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors duration-200">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>

            {{-- Standard Premium Room --}}
            <div
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                <div class="relative overflow-hidden h-56 bg-yellow-50">
                    <img src="{{ asset('assets/images/pRoom.png') }}" alt="Standard Room"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span
                        class="absolute top-4 left-4 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                        Standard Premium
                    </span>
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Standard Premium Room</h3>
                    <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                        A clean, comfortable room with all essential amenities — perfect for solo travelers or couples
                        on a budget.
                    </p>
                    <ul class="flex flex-wrap gap-2 mb-5 text-xs text-gray-500">
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> Free Wi-Fi
                        </li>
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> Air Conditioning
                        </li>
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> Free Wi-Fi
                        </li>
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> Air Conditioning
                        </li>
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> 1 Queen Bed
                        </li>
                    </ul>
                    <div class="flex items-center justify-between mt-auto">
                        <div>
                            <span class="text-2xl font-bold text-gray-900">₱1,500</span>
                            <span class="text-gray-400 text-sm"> / night</span>
                        </div>
                        <a href=""
                            class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors duration-200">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>

            {{-- Family Room --}}
            <div
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                <div class="relative overflow-hidden h-56 bg-yellow-50">
                    <img src="{{ asset('assets/images/fRoom.png') }}" alt="Standard Room"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span
                        class="absolute top-4 left-4 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                        Family
                    </span>
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Family Room</h3>
                    <p class="text-gray-500 text-sm mb-4 leading-relaxed">
                        A clean, comfortable room with all essential amenities — perfect for solo travelers or couples
                        on a budget.
                    </p>
                    <ul class="flex flex-wrap gap-2 mb-5 text-xs text-gray-500">
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> Free Wi-Fi
                        </li>
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> Air Conditioning
                        </li>
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> Free Wi-Fi
                        </li>
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> Air Conditioning
                        </li>
                        <li class="flex items-center gap-1 bg-yellow-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500">★</span> 1 Queen Bed
                        </li>
                    </ul>
                    <div class="flex items-center justify-between mt-auto">
                        <div>
                            <span class="text-2xl font-bold text-gray-900">₱1,500</span>
                            <span class="text-gray-400 text-sm"> / night</span>
                        </div>
                        <a href=""
                            class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors duration-200">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="flex flex-col md:flex-row items-center px-6 md:px-16 py-20 gap-10">

        <div class="flex-1">
            <h2 class="text-3xl md:text-4xl text-center md:text-left font-bold mb-6">
                Why Choose <span class="text-primary">Caree Hotel?</span>
            </h2>

            <div class="flex gap-4 mb-8">
                <div
                    class="bg-primary w-12 h-12 flex items-center justify-center rounded-full text-black flex-shrink-0">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Micro Pricing Engine</h3>
                    <p class="text-gray-600">
                        Our innovative pricing model breaks down room rates by features like view, type, and ambiance.
                    </p>
                </div>
            </div>

            <div class="flex gap-4 mb-8">
                <div
                    class="bg-primary w-12 h-12 flex items-center justify-center rounded-full text-black flex-shrink-0">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Secure Identity Verification</h3>
                    <p class="text-gray-600">
                        Two-step identity verification ensures safe and legitimate bookings.
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div
                    class="bg-primary w-12 h-12 flex items-center justify-center rounded-full text-black flex-shrink-0">
                    <i class="fas fa-bed"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Bulan's Finest Comfort</h3>
                    <p class="text-gray-600">
                        High-speed internet, premium bedding, and peaceful ambiance in Sorsogon.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex-1">
            <img src="{{ asset('assets/images/ch1.png') }}"
                class="rounded-3xl w-full max-h-[80vh] object-cover hover:scale-105 transition-transform shadow-lg">
        </div>
    </section>

    <section class="min-h-screen bg-cover bg-center flex items-center px-6 md:px-16 text-white"
        style="background-image: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.75)), url('{{ asset('assets/images/about-bg.jpg') }}')">

        <div class="max-w-3xl">
            <p class="uppercase tracking-[0.3em] text-primary font-semibold mb-3">
                Our Story
            </p>

            <h1 class="text-4xl md:text-6xl font-bold leading-tight">
                A Sanctuary of Serenity in
                <span class="text-primary">Bulan, Sorsogon</span>
            </h1>

            <p class="mt-6 text-sm md:text-lg leading-relaxed text-gray-200">
                Founded in 2016, Caree Hotel was established with a singular vision:
                to bring international standards of hospitality to the heart of Bulan.
                We provide a peaceful and comfortable environment where every guest
                can experience rest, relaxation, and peace of mind.
            </p>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/10">
                    <h3 class="text-xl font-bold text-primary">2016</h3>
                    <p class="text-sm text-gray-200 mt-2">
                        Established to deliver quality hospitality and comfort.
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/10">
                    <h3 class="text-xl font-bold text-primary">Mission</h3>
                    <p class="text-sm text-gray-200 mt-2">
                        Promoting excellence in the local hospitality industry.
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/10">
                    <h3 class="text-xl font-bold text-primary">Innovation</h3>
                    <p class="text-sm text-gray-200 mt-2">
                        Micro Pricing lets guests customize their stay experience.
                    </p>
                </div>
            </div>

            <div class="mt-8 flex flex-col md:flex-row gap-4">
                <button
                    class="w-full md:w-auto md:min-w-[190px] bg-primary text-white px-6 py-3 rounded-full font-bold hover:bg-secondary hover:text-black hover:scale-105 transition duration-300">
                    Read More About Us
                </button>

                <button
                    class="w-full md:w-auto md:min-w-[190px] border border-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-black hover:scale-105 transition duration-300">
                    Contact Us
                </button>
            </div>
        </div>
    </section>

    <footer class="bg-black text-white px-6 md:px-16 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="/">
                <img src="{{ asset('assets/images/chlogo.png') }}" class="w-28">
            </a>

            <p class="text-xs">&copy; 2026 Caree Hotel. All rights reserved.</p>

            <div class="space-x-4">
                <a href="#" class="hover:text-primary text-sm">Privacy Policy</a>
                <a href="#" class="hover:text-primary text-sm">Terms of Service</a>
            </div>
        </div>
    </footer>

</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Caree Hotel' }} | Caree Hotel</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/images/chlogo.png') }}">


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
</head>

<body class="bg-white text-black font-sans">

    <nav class="fixed w-full top-0 bg-white shadow z-50 px-6 md:px-16 py-4 flex justify-between items-center">

        <a href="/">
            <img src="{{ asset('assets/images/chlogo.png') }}" class="w-20">
        </a>

        <div class="space-x-6 font-medium">
            <a href="#" class="hover:text-primary">HOME</a>
            <a href="#" class="hover:text-primary">ROOMS</a>
            <a href="{{ route('login') }}" class="hover:text-primary ps-5">LOGIN</a>
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
                <button class="w-full md:w-auto md:min-w-[180px] bg-primary text-white px-6 py-3 rounded-full font-bold hover:bg-secondary hover:scale-105 hover:text-black transition text-center">
                    Start Reservation
                </button>
                <button class="w-full md:w-auto md:min-w-[180px] border border-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-black hover:scale-105 transition text-center">
                    Explore Rooms
                </button>
            </div>
        </div>
    </section>

    <section class="flex flex-col md:flex-row items-center px-6 md:px-16 py-20 gap-10">

        <div class="flex-1">
            <h2 class="text-3xl md:text-4xl text-center md:text-left font-bold mb-6">
                Why Choose <span class="text-primary">Caree Hotel?</span>
            </h2>

            <div class="flex gap-4 mb-8">
                <div class="bg-primary w-12 h-12 flex items-center justify-center rounded-full text-black flex-shrink-0">
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
                <div class="bg-primary w-12 h-12 flex items-center justify-center rounded-full text-black flex-shrink-0">
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
                <div class="bg-primary w-12 h-12 flex items-center justify-center rounded-full text-black flex-shrink-0">
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

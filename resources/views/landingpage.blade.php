<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Caree Hotel' }} | Caree Hotel</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/images/chlogo.png') }}">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ffd000',
                        secondary: '#f9ae36',
                        light: '#ffe1a4',
                        dark: '#000000'
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
            <a href="#" class="hover:text-primary ps-5">LOGIN</a>
        </div>
    </nav>

    <section class="h-screen bg-cover bg-center flex items-center px-6 md:px-16 text-white"
        style="background-image: linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.5)), url('{{ asset('assets/images/ch2.png') }}')">

        <div class="max-w-xl">
            <h1 class="text-4xl md:text-6xl font-bold mt-10">
                FIND YOUR <span class="text-primary">PEACE</span> HERE.
            </h1>

            <p class="mt-4 text-lg leading-relaxed">
                Experience the perfect blend of modern comfort and serene ambiance.
                Our micro-pricing ensures you only pay for the views and features you love.
            </p>

            <div class="mt-6 space-x-3">
                <button class="bg-primary text-white px-6 py-3 rounded-full font-bold hover:bg-secondary hover:shadow-lg hover:text-black transition">
                    Start Reservation
                </button>
                <button class="border border-white px-6 py-3 rounded-full font-bold hover:bg-white hover:text-black transition">
                    Explore Rooms
                </button>
            </div>
        </div>
    </section>

    <section class="flex flex-col md:flex-row items-center px-6 md:px-16 py-20 gap-10">

        <div class="flex-1">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
                Why Choose <span class="text-primary">Caree Hotel?</span>
            </h2>

            <div class="flex gap-4 mb-8">
                <div class="bg-primary w-12 h-12 flex items-center justify-center rounded-full text-black flex-shrink-0">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h3 class="font-semibold">Micro Pricing Engine</h3>
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
                    <h3 class="font-semibold">Secure Identity Verification</h3>
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
                    <h3 class="font-semibold">Bulan's Finest Comfort</h3>
                    <p class="text-gray-600">
                        High-speed internet, premium bedding, and peaceful ambiance in Sorsogon.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex-1">
            <img src="{{ asset('assets/images/ch1.png') }}"
                class="rounded-3xl w-full max-h-[80vh] object-cover">
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

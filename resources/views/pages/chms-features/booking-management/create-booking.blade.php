@extends('layouts.authenticated.app')

@section('title', 'Create Walk-in Booking')

@section('content')
    <x-common.page-breadcrumb pageTitle="Create Booking" />

    <div
        class="rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] sm:px-8 sm:py-10">
        <div class="w-full">

            {{-- Page Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create Walk-In Booking</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Fill in the details below to create a new room
                    booking.</p>
            </div>

            {{-- Form Card --}}
            <form method="POST" action="{{ route('walk-in-booking.store') }}" class="space-y-6">
                @csrf

                {{-- ── SECTION 1: GUEST DETAILS ── --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Full Name --}}
                    <div>
                        <label for="fullname" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </span>
                            <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}"
                                placeholder="Juan Dela Cruz" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20">
                        </div>
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label for="phone_number" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 4.5c0 8.146 6.604 14.75 14.75 14.75h2.25A2.25 2.25 0 0 0 21.5 17v-1.372a1.5 1.5 0 0 0-1.233-1.477l-3.078-.56a1.5 1.5 0 0 0-1.462.622l-.675.9a12.01 12.01 0 0 1-5.165-5.165l.9-.675a1.5 1.5 0 0 0 .622-1.462l-.56-3.078A1.5 1.5 0 0 0 8.372 2.5H7A2.25 2.25 0 0 0 4.75 4.75V4.5" />
                                </svg>
                            </span>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                                placeholder="09XXXXXXXXX" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20">
                        </div>
                    </div>
                </div>

                {{-- ── SECTION 2: ROOM SELECTION ── --}}
                <div class="space-y-4">
                    {{-- Room Selection Input --}}
                    <div>
                        <label for="room_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Room No. <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12" />
                                </svg>
                            </span>
                            <select id="room_id" name="room_id" required
                                class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-9 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20">
                                <option value="" data-price="0">Select Room</option>
                                @foreach ($rooms as $room)
                                    {{-- Added data-price attribute here --}}
                                    <option value="{{ $room->id }}" data-price="{{ $room->base_price }}">
                                        Room No.: {{ $room->room_no }} - Php. {{ number_format($room->base_price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <span
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Room Details Meta Display (Populated automatically via JS depending on room picked) --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        {{-- Check In Date --}}
                        <div>
                            <label for="check_in" class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">
                                Check-in <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10.5V6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25V10.5Z" />
                                    </svg>
                                </span>
                                <input type="date" id="check_in" name="check_in"
                                min="{{ date('Y-m-d') }}"
                                    value="{{ old('check_in') }}"required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2 pl-9 pr-3 text-xs text-gray-700 dark:text-gray-300 dark:border-gray-800 dark:bg-white/[0.03] outline-none cursor-pointer focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20">
                            </div>
                        </div>

                        {{-- Check-out Date --}}
                        <div>
                            <label for="check_out" class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">
                                Check-out <span class="text-red-500">*</span>
                                <span id="nights_badge" class="ml-2 font-bold text-yellow-600 hidden">(0 nights)</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 21h18M5 21V7l7-4 7 4v14" />
                                    </svg>
                                </span>
                                <input type="date" id="check_out" name="check_out" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2 pl-9 pr-3 text-xs text-gray-700 dark:text-gray-300 dark:border-gray-800 dark:bg-white/[0.03] outline-none cursor-pointer focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20">
                            </div>
                        </div>


                        {{-- Number of Guests --}}
                        <div>
                            <label for="number_of_guests"
                                class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Number of
                                Guests</label>
                            <div class="relative">
                                <span
                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 20a6 6 0 0 0-12 0m9-12a3 3 0 1 1-6 0m13 12a4.5 4.5 0 0 0-3-4.243m1.5-7.257a2.25 2.25 0 1 1 0-4.5" />
                                    </svg>
                                </span>

                                <input type="number" id="number_of_guests" name="number_of_guests"
                                    value="{{ old('number_of_guests', 1) }}" min="1" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-100 py-2 pl-9 pr-3 text-xs text-gray-600 outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── SECTION 3: BOOKING SETTINGS ── --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Ambiance --}}
                    <div>
                        <label for="ambiance" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Ambiance <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 4.5h18M6 9h12M8.25 13.5h7.5M10.5 18h3" />
                                </svg>
                            </span>
                            <select id="ambiance" name="ambiance" required
                                class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-10 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20">
                                <option value="" data-price="0">Select Ambiance</option>
                                @foreach ($ambiance as $amb => $price)
                                    {{-- Added data-price attribute here --}}
                                    <option value="{{ $amb }}" data-price="{{ $price }}">
                                        {{ $amb }} - Php. {{ number_format($price, 2) }}</option>
                                @endforeach
                            </select>
                            <span
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Food Package --}}
                    <div>
                        <label for="food_package"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Food Package <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 4.5h18M6 9h12M8.25 13.5h7.5M10.5 18h3" />
                                </svg>
                            </span>
                            <select id="food_package" name="food_package" required
                                class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-10 text-sm focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20">
                                <option value="" data-price="0">Select Package</option>
                                @foreach ($food_package as $food => $price)
                                    {{-- Added data-price attribute here --}}
                                    <option value="{{ $food }}" data-price="{{ $price }}">
                                        {{ $food }} - Php. {{ number_format($price, 2) }}</option>
                                @endforeach
                            </select>
                            <span
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ── SECTION 4: PRICING INFORMATION ── --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    {{-- Room Price --}}
                    <div>
                        <label for="room_price"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Room Price</label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-gray-500">₱</span>
                            <input type="number" id="room_price" name="room_price" readonly step="0.01"
                                value="{{ old('room_price', '0.00') }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-100 py-2.5 pl-8 pr-4 text-sm outline-none text-gray-600">
                        </div>
                    </div>

                    {{-- Micro Pricing --}}
                    <div>
                        <label for="micro_pricing_amount"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Micro Pricing</label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-gray-500">₱</span>
                            <input type="number" id="micro_pricing_amount" name="micro_pricing_amount" readonly
                                step="0.01" value="0.00"
                                class="w-full rounded-xl border border-gray-200 bg-gray-100 py-2.5 pl-8 pr-4 text-sm outline-none text-gray-600">
                        </div>
                    </div>

                    {{-- Total Price --}}
                    <div>
                        <label for="total_price"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Total Price</label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-semibold text-yellow-600">₱</span>
                            <input type="number" id="total_price" name="total_price" readonly step="0.01"
                                value="0.00"
                                class="w-full rounded-xl border-2 border-yellow-300 bg-yellow-50 py-2.5 pl-8 pr-4 text-sm font-bold text-yellow-700 outline-none">
                        </div>
                    </div>

                </div>
                {{-- Remarks Block (Just below the prices) --}}
                <div class="w-full"> {{-- This ensures it takes 100% width --}}
                    <label for="remarks" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Remarks <span class="text-xs font-normal text-gray-400 dark:text-gray-500">(Optional)</span>
                    </label>
                    <div class="relative w-full">
                        <span class="pointer-events-none absolute top-3 left-3.5 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                            </svg>
                        </span>
                        <textarea id="remarks" name="remarks" rows="3" placeholder="Add custom notes..."
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm placeholder-gray-400 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 dark:border-gray-800 dark:bg-white/[0.03] outline-none transition resize-none"></textarea>
                    </div>
                </div>

                {{-- ── Divider ── --}}
                <div class="border-t border-gray-100 dark:border-gray-800"></div>

                {{-- ── Action Buttons ── --}}
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href=""
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-transparent dark:text-gray-300 dark:hover:bg-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-yellow-400 px-6 py-2.5 text-sm font-semibold text-gray-900 transition hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 active:scale-95 dark:focus:ring-offset-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                        </svg>
                        Create Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Selection Inputs
            const roomSelect = document.getElementById('room_id');
            const ambianceSelect = document.getElementById('ambiance');
            const foodPackageSelect = document.getElementById('food_package');

            // Date Inputs
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');
            const nightsBadge = document.getElementById('nights_badge');

            // Pricing Displays
            const roomPriceInput = document.getElementById('room_price');
            const microPricingInput = document.getElementById('micro_pricing_amount');
            const totalPriceInput = document.getElementById('total_price');

            // Force native calendar display behavior when clicking the input wrappers
            [checkInInput, checkOutInput].forEach(input => {
                // Show picker on click
                input.addEventListener('click', function() {
                    if (typeof this.showPicker === 'function') {
                        this.showPicker();
                    }
                });
                // Show picker when input gains focus via tab key navigation
                input.addEventListener('focus', function() {
                    if (typeof this.showPicker === 'function') {
                        this.showPicker();
                    }
                });
            });

            function calculatePrices() {
                // 1. Calculate Nights from Dates
                let nights = 0;
                const checkInDate = new Date(checkInInput.value);
                const checkOutDate = new Date(checkOutInput.value);

                // Ensure check-out minimum boundary is reactive to check-in selection
                if (checkInInput.value) {
                    checkOutInput.min = checkInInput.value;
                }

                // Run calculation if both dates are valid code entries
                if (!isNaN(checkInDate) && !isNaN(checkOutDate)) {
                    const timeDifference = checkOutDate.getTime() - checkInDate.getTime();
                    // Convert milliseconds to days
                    nights = Math.ceil(timeDifference / (1000 * 3600 * 24));

                    if (nights < 0) nights = 0; // Guard clause against invalid ranges
                }

                // Update the visual indicator badge for clarity
                if (nights > 0) {
                    nightsBadge.textContent = `(${nights} ${nights === 1 ? 'night' : 'nights'})`;
                    nightsBadge.classList.remove('hidden');
                } else {
                    nightsBadge.classList.add('hidden');
                }

                // 2. Get Selected Room Base Price
                const selectedRoom = roomSelect.options[roomSelect.selectedIndex];
                const basePrice = parseFloat(selectedRoom.getAttribute('data-price')) || 0;

                // Multiply room price by amount of nights spent (default to 1 night minimum loop fallback if no dates picked yet)
                const totalRoomPrice = basePrice * (nights > 0 ? nights : 1);

                // 3. Get Ambiance and Food Micro Prices
                const selectedAmbiance = ambianceSelect.options[ambianceSelect.selectedIndex];
                const ambiancePrice = parseFloat(selectedAmbiance.getAttribute('data-price')) || 0;

                const selectedFood = foodPackageSelect.options[foodPackageSelect.selectedIndex];
                const foodPrice = parseFloat(selectedFood.getAttribute('data-price')) || 0;

                // Combine additional extras
                const microPricing = ambiancePrice + foodPrice;

                // 4. Compute overall Total Price
                const totalPrice = totalRoomPrice + microPricing;

                // 5. Update UI inputs (formatted to 2 decimal places)
                roomPriceInput.value = totalRoomPrice.toFixed(2);
                microPricingInput.value = microPricing.toFixed(2);
                totalPriceInput.value = totalPrice.toFixed(2);
            }

            // Attach event listeners to all status triggers
            roomSelect.addEventListener('change', calculatePrices);
            ambianceSelect.addEventListener('change', calculatePrices);
            foodPackageSelect.addEventListener('change', calculatePrices);
            checkInInput.addEventListener('change', calculatePrices);
            checkOutInput.addEventListener('change', calculatePrices);
        });
    </script>
@endsection

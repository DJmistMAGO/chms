@extends('layouts.authenticated.app')

@section('title', 'Create Walk-in Booking')

@section('content')
    <x-common.page-breadcrumb pageTitle="Create Booking" />
    <div
        class="rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
        <div class="mx-auto w-full">

            {{-- Page Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create Walk-In Booking</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Fill in the details below to create a new room
                    booking.</p>
            </div>

            {{-- Form Card --}}
            <form method="POST" action="" class="space-y-6" data-confirm-leave>
                @csrf

                {{-- ── Row 1: Full Name + Room No ── --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Full Name --}}
                    <div>
                        <label for="fullname" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Full Name
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 dark:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </span>
                            <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}"
                                placeholder="e.g. Juan dela Cruz" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 transition focus:border-yellow-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white dark:placeholder-gray-500 dark:focus:border-yellow-400 dark:focus:bg-gray-800 @error('fullname') border-red-400 focus:border-red-400 focus:ring-red-400/20 @enderror">
                        </div>
                        @error('fullname')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Room No --}}
                    <div>
                        <label for="room_no" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Room No.
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 dark:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                </svg>
                            </span>
                            <select id="room_no" name="room_no" required
                                class="w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-9 text-sm text-gray-900 transition focus:border-yellow-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white dark:focus:border-yellow-400 dark:focus:bg-gray-800 @error('room_no') border-red-400 focus:border-red-400 focus:ring-red-400/20 @enderror">
                                <option value="" disabled selected>Select room</option>
                                @foreach ($rooms ?? [] as $room)
                                    <option value="{{ $room->id }}" {{ old('room_no') == $room->id ? 'selected' : '' }}>
                                        Room {{ $room->room_no }} — {{ $room->type }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Chevron --}}
                            <span
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 dark:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </div>
                        @error('room_no')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- ── Row 2: Check-in + Check-out ── --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Check-in --}}
                    <div>
                        <label for="check_in" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Check-in Date
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 dark:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                            </span>
                            <input type="date" id="check_in" name="check_in" value="{{ old('check_in') }}" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 transition focus:border-yellow-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white dark:focus:border-yellow-400 dark:focus:bg-gray-800 @error('check_in') border-red-400 focus:border-red-400 focus:ring-red-400/20 @enderror">
                        </div>
                        @error('check_in')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Check-out --}}
                    <div>
                        <label for="check_out" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Check-out Date
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 dark:text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                            </span>
                            <input type="date" id="check_out" name="check_out" value="{{ old('check_out') }}"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 transition focus:border-yellow-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white dark:focus:border-yellow-400 dark:focus:bg-gray-800 @error('check_out') border-red-400 focus:border-red-400 focus:ring-red-400/20 @enderror">
                        </div>
                        @error('check_out')
                            <p class="mt-1.5 flex items-center gap-1 text-xs text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- ── Row 3: Amount (full width) ── --}}
                <div>
                    <label for="amount" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Amount
                        <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <div class="relative">
                        <span
                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-medium text-gray-500 dark:text-gray-400">
                            ₱
                        </span>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" min="0"
                            step="0.01" placeholder="0.00" required
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-8 pr-4 text-sm text-gray-900 placeholder-gray-400 transition focus:border-yellow-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400/20 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white dark:placeholder-gray-500 dark:focus:border-yellow-400 dark:focus:bg-gray-800 @error('amount') border-red-400 focus:border-red-400 focus:ring-red-400/20 @enderror">
                    </div>
                    @error('amount')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- Duration & total hint (shown when both dates are filled) --}}
                    <div id="date-summary"
                        class="mt-3 hidden rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-3 dark:border-yellow-400/20 dark:bg-yellow-400/10">
                        <p class="text-xs text-yellow-800 dark:text-yellow-300">
                            <span class="font-medium">Duration:</span>
                            <span id="nights-count">—</span> night(s) &nbsp;·&nbsp;
                            <span class="font-medium">Total:</span>
                            ₱<span id="total-amount">—</span>
                        </p>
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

    {{-- ── JavaScript ── --}}
    <script>
        const checkinInput = document.getElementById('check_in');
        const checkoutInput = document.getElementById('check_out');
        const amountInput = document.getElementById('amount');
        const summary = document.getElementById('date-summary');
        const nightsEl = document.getElementById('nights-count');
        const totalEl = document.getElementById('total-amount');

        // Set today as default check-in min
        const today = new Date().toISOString().split('T')[0];
        checkinInput.min = today;

        function updateSummary() {
            const cin = checkinInput.value;
            const cout = checkoutInput.value;
            const amount = parseFloat(amountInput.value) || 0;

            if (!cin || !cout) {
                summary.classList.add('hidden');
                return;
            }

            const nights = Math.round(
                (new Date(cout) - new Date(cin)) / (1000 * 60 * 60 * 24)
            );

            if (nights <= 0) {
                summary.classList.add('hidden');
                return;
            }

            summary.classList.remove('hidden');
            nightsEl.textContent = nights;
            totalEl.textContent = amount > 0 ?
                (nights * amount).toLocaleString('en-PH', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) :
                '—';
        }

        // Prevent checkout before check-in
        checkinInput.addEventListener('change', function() {
            checkoutInput.min = this.value;
            // If checkout is already before new check-in, push it forward
            if (checkoutInput.value && checkoutInput.value <= this.value) {
                const next = new Date(this.value);
                next.setDate(next.getDate() + 1);
                checkoutInput.value = next.toISOString().split('T')[0];
            }
            updateSummary();
        });

        checkoutInput.addEventListener('change', updateSummary);
        amountInput.addEventListener('input', updateSummary);
    </script>
@endsection

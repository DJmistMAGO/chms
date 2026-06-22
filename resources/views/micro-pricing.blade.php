<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $roomName ?? 'Customize Your Stay' }} | Caree Hotel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/hotel-datepicker@4.12.2/dist/css/hotel-datepicker.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/images/chlogo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold:     { DEFAULT: '#FFD000', light: '#FFE566', dark: '#B89200', faint: '#FFFAE6' },
                        cream:    '#FFFDF0',
                        charcoal: '#1C1C1E',
                        warm:     '#3D3530',
                        muted:    '#7A6E68',
                        panel:    '#FFF8D6',
                    },
                    fontFamily: {
                        display: ['Cormorant Garamond', 'serif'],
                        body:    ['DM Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    @include('components.devtools-protection')

    <style>
        * { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Cormorant Garamond', serif; }
        .option-row { cursor: pointer; transition: background 0.18s ease, border-color 0.18s ease; }
        .option-row.selected { background: #FFF3A3; border-color: #D4A800 !important; }
        .option-row:not(.selected):hover { background: #FFFAE6; }
        .option-row .dot { opacity: 0; transition: opacity 0.18s ease; }
        .option-row.selected .dot { opacity: 1; background: #D4A800; }
        .price-bump { transition: transform 0.2s ease; }
        .price-bump.bump { transform: scale(1.15); }
        .room-img-wrap:hover img { transform: scale(1.04); }
        .room-img-wrap img { transition: transform 0.5s ease; }
        .section-label { display: flex; align-items: center; gap: 10px; }
        .section-label::after { content: ''; flex: 1; height: 1px; background: #FFE566; }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #FFD000 !important;
            box-shadow: 0 0 0 3px rgba(255,208,0,0.18);
        }
        .sticky-bar { box-shadow: 0 -8px 32px rgba(0,0,0,0.08); }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(18px); }
            to { opacity:1; transform:translateY(0); }
        }
        .fade-up-1 { animation: fadeUp 0.5s ease both; }
        .fade-up-2 { animation: fadeUp 0.5s 0.08s ease both; }

        .field-error {
            margin-top: 6px;
            font-size: 12px;
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .field-error i {
            font-size: 10px;
        }
    </style>
</head>

<body class="text-warm font-body antialiased min-h-screen pb-32 lg:pb-0" style="background:#FFFDF0;">

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-40 backdrop-blur border-b px-4 md:px-10 py-3 flex items-center gap-4"
        style="background:rgba(255,253,240,0.92); border-color:#FFE566;">
        <a href="{{ route('landingpage') }}"
            class="flex items-center gap-2 text-sm font-medium text-warm hover:text-yellow-700 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Exit</span>
        </a>
        <div class="flex-1"></div>
        <img src="{{ asset('assets/images/chlogo.png') }}" class="w-20 opacity-80" alt="Caree Hotel">
    </nav>

    {{-- MAIN --}}
    <div class="max-w-6xl mx-auto px-4 md:px-8 py-6 md:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">

            {{-- Success --}}
            @if (session('success'))
                <div class="fixed inset-0 z-50 flex items-center justify-center px-4">
                    <div class="bg-green-500 text-white px-6 py-4 rounded-xl shadow-xl">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- ===== LEFT: Room Info ===== --}}
            <div class="fade-up-1">
                {{-- Image --}}
                <div class="room-img-wrap relative rounded-2xl overflow-hidden mb-5 shadow-md" style="height:280px;">
                    <img src="{{ asset($room->image ?? 'assets/images/pRoom.png') }}"
                        alt="{{ $roomName ?? 'Room' }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    <span class="absolute top-4 right-4 text-xs font-medium px-4 py-1.5 rounded-full tracking-widest uppercase"
                        style="background:#1C1C1E; color:#FAF7F2;">
                        {{ $roomName ?? 'Standard' }}
                    </span>
                    <div class="absolute bottom-4 left-4">
                        <p class="text-white/60 text-xs tracking-wide uppercase mb-0.5">Starting from</p>
                        <p class="font-display text-3xl font-semibold text-white">
                            ₱{{ number_format($price) }}
                            <span class="text-white/50 text-sm font-body font-light">/night</span>
                        </p>
                    </div>
                </div>

                {{-- Title --}}
                <h1 class="font-display text-3xl md:text-4xl font-semibold text-charcoal leading-tight mb-4">
                    {{ $roomName ?? 'Premium Deluxe Room' }}
                </h1>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="rounded-xl p-3 text-center" style="background:#FFF8D6; border:1px solid #FFE566;">
                        <i class="fas fa-expand-arrows-alt text-sm mb-1" style="color:#D4A800;"></i>
                        <p class="text-xs text-muted">Size</p>
                        <p class="text-sm font-medium text-charcoal">{{ $room->size ?? '30' }} sqm</p>
                    </div>
                    <div class="rounded-xl p-3 text-center" style="background:#FFF8D6; border:1px solid #FFE566;">
                        <i class="fas fa-bed text-sm mb-1" style="color:#D4A800;"></i>
                        <p class="text-xs text-muted">Bed</p>
                        <p class="text-sm font-medium text-charcoal">{{ $room->bed ?? '1 King Bed' }}</p>
                    </div>
                    <div class="rounded-xl p-3 text-center" style="background:#FFF8D6; border:1px solid #FFE566;">
                        <i class="fas fa-user-friends text-sm mb-1" style="color:#D4A800;"></i>
                        <p class="text-xs text-muted">Capacity</p>
                        <p class="text-sm font-medium text-charcoal">{{ $room->capacity ?? '2' }} guests</p>
                    </div>
                </div>

                {{-- Amenities --}}
                <div>
                    <p class="text-xs font-medium tracking-widest uppercase mb-3" style="color:#B89200;">Amenities</p>
                    <div class="grid grid-cols-2 gap-y-2 gap-x-4">
                        @foreach($room->amenities ?? ['Air Conditioning','Cable TV','Toilet','Bathtub','High-Speed WiFi','Private Bathroom','Work Desk'] as $amenity)
                            <div class="flex items-center gap-2 text-sm text-warm">
                                <i class="fas fa-check" style="font-size:11px; flex-shrink:0; color:#D4A800;"></i>
                                {{ $amenity }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT: Customize Panel ===== --}}
            <div class="fade-up-2">

                {{-- Top-level validation summary for booking errors --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        <p class="font-semibold text-sm mb-1">Please fix the following:</p>
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Hidden booking values on page side --}}
                <input type="hidden" name="room_type" value="{{ $roomName ?? '' }}">
                <input type="hidden" name="check_in" id="check_in" value="{{ old('check_in') }}">
                <input type="hidden" name="check_out" id="check_out" value="{{ old('check_out') }}">
                <input type="hidden" name="number_of_guests" id="input-guests" value="{{ old('number_of_guests', 1) }}">
                <input type="hidden" name="nights" id="input-nights" value="{{ old('nights', 0) }}">
                <input type="hidden" name="floor_level" id="input-floor" value="{{ old('floor_level', 'Floor 1') }}">
                <input type="hidden" name="ambiance" id="input-ambiance" value="{{ old('ambiance', 'Regular Room') }}">
                <input type="hidden" name="food_package" id="input-food" value="{{ old('food_package', 'No Food') }}">
                <input type="hidden" name="room_price" value="{{ $price }}">
                <input type="hidden" name="micro_pricing_amount" id="input-addons" value="{{ old('micro_pricing_amount', 0) }}">
                <input type="hidden" name="total_price" id="input-total" value="{{ old('total_price', $price) }}">

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #FFE566;">

                    {{-- Header --}}
                    <div class="px-6 py-5 bg-gradient-to-bl from-amber-300 via-transparent to-amber-500">
                        <h2 class="font-display text-2xl font-semibold text-charcoal">Customize Your Stay</h2>
                        <p class="text-xs mt-1 font-light" style="color:rgba(28,28,30,0.55);">
                            Select your preferences to see transparent pricing breakdown
                        </p>
                    </div>

                    <div class="px-6 py-6 space-y-7">

                        {{-- GUESTS --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Guests</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button"
                                    onclick="stepGuests(-1)"
                                    class="w-10 h-10 rounded-xl border flex items-center justify-center text-lg font-semibold text-warm transition hover:bg-yellow-50"
                                    style="border-color:#FFE566;">−</button>

                                <input type="number"
                                    id="pax_count"
                                    value="{{ old('number_of_guests', 1) }}"
                                    min="1"
                                    max="{{ $room->capacity ?? 10 }}"
                                    class="flex-1 border rounded-xl py-2.5 text-center text-sm font-semibold text-charcoal"
                                    style="border-color:#FFE566; background:#FFF8D6;">

                                <button type="button"
                                    onclick="stepGuests(1)"
                                    class="w-10 h-10 rounded-xl border flex items-center justify-center text-lg font-semibold text-warm transition hover:bg-yellow-50"
                                    style="border-color:#FFE566;">+</button>
                            </div>
                            @error('number_of_guests')
                                <p class="field-error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        {{-- DATES --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Dates</span>
                            </div>

                            @php
                                $oldDateRange = '';
                                if (old('check_in') && old('check_out')) {
                                    try {
                                        $oldDateRange = \Carbon\Carbon::parse(old('check_in'))->format('M j, Y') . ' - ' .
                                                        \Carbon\Carbon::parse(old('check_out'))->format('M j, Y');
                                    } catch (\Throwable $e) {
                                        $oldDateRange = '';
                                    }
                                }
                            @endphp

                            <input type="text" id="booking_range"
                                placeholder="Select check-in → check-out"
                                readonly
                                value="{{ $oldDateRange }}"
                                class="w-full border rounded-xl px-4 py-3 text-sm text-warm cursor-pointer"
                                style="border-color:#FFE566; background:#FFF8D6;">

                            <p id="nights-badge" class="hidden mt-2 text-xs font-medium" style="color:#B89200;">
                                <i class="fas fa-moon mr-1"></i>
                                <span id="nights-label"></span>
                            </p>

                            @error('check_in')
                                <p class="field-error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror

                            @error('check_out')
                                <p class="field-error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror

                            @error('nights')
                                <p class="field-error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        {{-- FLOOR LEVEL --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Floor Level</span>
                            </div>
                            <div class="space-y-2">
                                @php $selectedFloor = old('floor_level', 'Floor 1'); @endphp

                                @foreach (['Floor 1', 'Floor 2', 'Floor 4'] as $floor)
                                    <div class="option-row {{ $selectedFloor === $floor ? 'selected' : '' }} flex items-center justify-between border rounded-xl px-4 py-3"
                                        style="border-color:{{ $selectedFloor === $floor ? '#D4A800' : '#FFE566' }};"
                                        data-group="floor_level"
                                        data-price="0">
                                        <div class="flex items-center gap-3">
                                            <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                            <span class="text-sm {{ $selectedFloor === $floor ? 'font-medium' : '' }} text-warm">{{ $floor }}</span>
                                        </div>
                                        <span class="badge text-xs font-medium px-3 py-1 rounded-full"
                                            style="background:{{ $selectedFloor === $floor ? '#FFF3A3' : '#FFF8D6' }}; color:{{ $selectedFloor === $floor ? '#B89200' : '#7A6E68' }};">
                                            Free
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            @error('floor_level')
                                <p class="field-error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        {{-- AMBIANCE --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Ambiance</span>
                            </div>
                            <div class="space-y-2">
                                @php $selectedAmbiance = old('ambiance', 'Regular Room'); @endphp

                                @foreach ([
                                    ['label' => 'Regular Room', 'price' => 0, 'tag' => 'Base'],
                                    ['label' => 'Cozy Ambiance', 'price' => 500, 'tag' => '+ ₱500'],
                                    ['label' => 'Romantic Ambiance', 'price' => 1000, 'tag' => '+ ₱1,000'],
                                ] as $ambiance)
                                    <div class="option-row {{ $selectedAmbiance === $ambiance['label'] ? 'selected' : '' }} flex items-center justify-between border rounded-xl px-4 py-3"
                                        style="border-color:{{ $selectedAmbiance === $ambiance['label'] ? '#D4A800' : '#FFE566' }};"
                                        data-group="ambiance"
                                        data-price="{{ $ambiance['price'] }}">
                                        <div class="flex items-center gap-3">
                                            <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                            <span class="text-sm {{ $selectedAmbiance === $ambiance['label'] ? 'font-medium' : '' }} text-warm">{{ $ambiance['label'] }}</span>
                                        </div>
                                        <span class="badge text-xs font-medium px-3 py-1 rounded-full"
                                            style="background:{{ $selectedAmbiance === $ambiance['label'] ? '#FFF3A3' : '#FFF8D6' }}; color:{{ $selectedAmbiance === $ambiance['label'] ? '#B89200' : '#7A6E68' }};">
                                            {{ $ambiance['price'] == 0 ? 'Base' : '+ ₱' . number_format($ambiance['price']) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            @error('ambiance')
                                <p class="field-error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        {{-- FOOD PACKAGE --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Food Package</span>
                            </div>
                            <div class="space-y-2">
                                @php $selectedFood = old('food_package', 'No Food'); @endphp

                                @foreach ([
                                    ['label' => 'No Food', 'price' => 0],
                                    ['label' => 'Cozy Dinner for Family', 'price' => 1500],
                                    ['label' => 'Romantic Dinner', 'price' => 1500],
                                ] as $food)
                                    <div class="option-row {{ $selectedFood === $food['label'] ? 'selected' : '' }} flex items-center justify-between border rounded-xl px-4 py-3"
                                        style="border-color:{{ $selectedFood === $food['label'] ? '#D4A800' : '#FFE566' }};"
                                        data-group="food_package"
                                        data-price="{{ $food['price'] }}">
                                        <div class="flex items-center gap-3">
                                            <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                            <span class="text-sm {{ $selectedFood === $food['label'] ? 'font-medium' : '' }} text-warm">{{ $food['label'] }}</span>
                                        </div>
                                        <span class="badge text-xs font-medium px-3 py-1 rounded-full"
                                            style="background:{{ $selectedFood === $food['label'] ? '#FFF3A3' : '#FFF8D6' }}; color:{{ $selectedFood === $food['label'] ? '#B89200' : '#7A6E68' }};">
                                            {{ $food['price'] == 0 ? 'Free' : '+ ₱' . number_format($food['price']) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            @error('food_package')
                                <p class="field-error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        {{-- PRICE BREAKDOWN + SUBMIT --}}
                        <div class="pt-5" style="border-top:1px solid #FFE566;">
                            <div class="rounded-2xl p-4 space-y-2 mb-5" style="background:#FFF8D6; border:1px solid #FFE566;">
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted">Rate / night</span>
                                    <span class="font-medium text-charcoal">₱{{ number_format($price) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted">Add-ons / night</span>
                                    <span class="font-medium text-charcoal" id="addon-display">₱0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-muted">Nights</span>
                                    <span class="font-medium text-charcoal" id="nights-display">—</span>
                                </div>
                                <div class="pt-2 flex justify-between items-center" style="border-top:1px solid #FFE566;">
                                    <span class="text-xs tracking-widest uppercase text-muted">Total</span>
                                    <span class="font-display text-2xl font-semibold price-bump" style="color:#B89200;" id="total-price">
                                        ₱{{ number_format($price) }}
                                    </span>
                                </div>
                            </div>

                            <button type="button" onclick="openSignInModal()"
                                class="w-full py-4 rounded-2xl font-medium tracking-wide active:scale-95 transition-all flex items-center justify-center gap-2 text-sm"
                                style="background:#FFD000; color:#1C1C1E;">
                                <i class="fas fa-calendar-check text-xs"></i>
                                Submit Reservation
                            </button>

                            <p class="text-center text-xs text-muted/70 mt-3">
                                Free cancellation up to 24 hours before check-in
                            </p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ===== SIGN-IN MODAL ===== --}}
    <div id="signin-overlay"
        class="hidden fixed inset-0 z-50 flex items-center justify-center px-4"
        style="background:rgba(28,28,30,0.55); backdrop-filter:blur(6px);">

        <div id="signin-modal"
            class="w-full max-w-md rounded-3xl overflow-hidden shadow-2xl"
            style="background:#FFFDF0; border:1px solid #FFE566;
                transform:translateY(24px); opacity:0;
                transition:transform 0.32s ease, opacity 0.32s ease;">

            {{-- Modal header --}}
            <div class="px-7 pt-7 pb-5 flex items-start justify-between bg-gradient-to-bl from-amber-300 via-transparent to-amber-500">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <img src="{{ asset('assets/images/chlogo.png') }}" class="w-8 opacity-90" alt="Caree Hotel">
                        <span class="text-xs font-bold tracking-widest uppercase text-charcoal/60">Caree Hotel</span>
                    </div>
                    <h3 class="font-display text-2xl font-semibold text-charcoal leading-tight">
                        Sign in to continue
                    </h3>
                    <p class="text-xs mt-1" style="color:rgba(28,28,30,0.5);">
                        Confirm your identity to complete the reservation
                    </p>
                </div>
                <button type="button" onclick="closeSignInModal()"
                    class="mt-1 w-8 h-8 flex items-center justify-center rounded-full transition hover:bg-black/10 text-charcoal/60 hover:text-charcoal flex-shrink-0">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            {{-- Booking summary strip --}}
            <div class="px-7 py-3 flex items-center gap-3 text-xs" style="background:#FFF8D6; border-bottom:1px solid #FFE566;">
                <i class="fas fa-calendar-check" style="color:#D4A800;"></i>
                <span class="text-muted">Booking summary:</span>
                <span class="font-semibold text-charcoal" id="modal-summary-room">{{ $roomName ?? 'Room' }}</span>
                <span class="text-muted mx-1">·</span>
                <span class="font-semibold" style="color:#B89200;" id="modal-summary-total">₱{{ number_format($price) }}</span>
            </div>

            {{-- Sign-in form --}}
            <div class="px-7 py-6">
                <form method="POST" action="{{ route('customize.login.with.booking') }}" id="signin-form" class="space-y-4" enctype="multipart/form-data">
                    @csrf

                    {{-- Hidden booking values --}}
                    <input type="hidden" name="room_type" value="{{ $roomName ?? '' }}">
                    <input type="hidden" name="check_in" id="modal-check_in" value="{{ old('check_in') }}">
                    <input type="hidden" name="check_out" id="modal-check_out" value="{{ old('check_out') }}">
                    <input type="hidden" name="number_of_guests" id="modal-guests" value="{{ old('number_of_guests', 1) }}">
                    <input type="hidden" name="nights" id="modal-nights" value="{{ old('nights', 0) }}">
                    <input type="hidden" name="floor_level" id="modal-floor" value="{{ old('floor_level', 'Floor 1') }}">
                    <input type="hidden" name="ambiance" id="modal-ambiance" value="{{ old('ambiance', 'Regular Room') }}">
                    <input type="hidden" name="food_package" id="modal-food" value="{{ old('food_package', 'No Food') }}">
                    <input type="hidden" name="room_price" value="{{ $price }}">
                    <input type="hidden" name="micro_pricing_amount" id="modal-addons" value="{{ old('micro_pricing_amount', 0) }}">
                    <input type="hidden" name="total_price" id="modal-total" value="{{ old('total_price', $price) }}">

                    {{-- General modal error --}}
                    @if ($errors->has('general'))
                        <div class="rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
                            {{ $errors->first('general') }}
                        </div>
                    @endif

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-medium tracking-widest uppercase mb-1.5" style="color:#7A6E68;">
                            Email Address
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5" style="color:#D4A800;">
                                <i class="fas fa-envelope text-xs"></i>
                            </span>
                            <input type="email"
                                name="email"
                                id="modal-email"
                                placeholder="you@email.com"
                                required
                                autocomplete="email"
                                value="{{ old('email') }}"
                                class="w-full rounded-xl pl-9 pr-4 py-3 text-sm text-warm placeholder-muted/50 transition-colors"
                                style="border:1px solid #FFE566; background:#FFF8D6;">
                        </div>
                        @error('email')
                            <p class="field-error">
                                <i class="fas fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-medium tracking-widest uppercase" style="color:#7A6E68;">
                                Password
                            </label>
                            <a href="{{ route('password.request') }}"
                                class="text-xs hover:underline transition-colors" style="color:#B89200;">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5" style="color:#D4A800;">
                                <i class="fas fa-lock text-xs"></i>
                            </span>
                            <input type="password"
                                name="password"
                                id="modal-password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                                class="w-full rounded-xl pl-9 pr-10 py-3 text-sm text-warm placeholder-muted/50 transition-colors"
                                style="border:1px solid #FFE566; background:#FFF8D6;">
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 transition-colors"
                                style="color:#D4A800;">
                                <i id="eye-icon" class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="field-error">
                                <i class="fas fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Valid ID upload INSIDE modal form --}}
                    <div>
                        <label class="block text-xs font-medium tracking-widest uppercase mb-1.5" style="color:#7A6E68;">
                            Valid ID
                        </label>

                        <label for="modal_valid_id"
                            class="flex flex-col items-center justify-center gap-2 w-full rounded-xl border-2 border-dashed cursor-pointer transition-colors hover:bg-yellow-50"
                            style="border-color:#FFE566; background:#FFF8D6; min-height:96px;"
                            id="modal-valid-id-label">

                            <div id="modal-valid-id-placeholder" class="flex flex-col items-center gap-2">
                                <i class="fas fa-id-card text-xl" style="color:#D4A800;"></i>
                                <span class="text-xs font-medium text-warm">Click to upload a valid ID</span>
                                <span class="text-xs text-muted">JPG, PNG or PDF · max 5MB</span>
                            </div>

                            <div id="modal-valid-id-preview-wrap" class="hidden items-center gap-3">
                                <img id="modal-valid-id-preview" src="" alt="ID preview" class="w-16 h-12 object-cover rounded-md border" />
                                <div class="flex flex-col items-start text-left">
                                    <span class="text-xs font-medium text-warm" id="modal-valid-id-name"></span>
                                    <button type="button" class="text-xs text-red-500 mt-1" onclick="clearModalIdUpload()">Remove</button>
                                </div>
                            </div>

                            <input type="file"
                                name="valid_id_path"
                                id="modal_valid_id"
                                accept="image/jpeg,image/png,application/pdf"
                                class="hidden"
                                onchange="handleModalIdUpload(this)">
                        </label>

                        <p class="hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="modal-valid-id-error">
                            <i class="fas fa-circle-exclamation" style="font-size:10px;"></i>
                            <span></span>
                        </p>

                        @error('valid_id_path')
                            <p class="field-error">
                                <i class="fas fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Remember me --}}
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" name="remember" id="modal-remember"
                            class="w-4 h-4 rounded accent-yellow-400"
                            {{ old('remember') ? 'checked' : '' }}>
                        <span class="text-sm text-warm">Remember me</span>
                    </label>

                    {{-- Submit --}}
                    <button type="submit" id="signin-submit"
                        class="w-full py-3.5 rounded-2xl font-medium tracking-wide active:scale-95 transition-all flex items-center justify-center gap-2 text-sm mt-2"
                        style="background:#FFD000; color:#1C1C1E;">
                        <i class="fas fa-arrow-right-to-bracket text-xs"></i>
                        Sign In & Confirm Booking
                    </button>

                    {{-- Divider --}}
                    {{-- <div class="flex items-center gap-3">
                        <div class="flex-1 h-px" style="background:#FFE566;"></div>
                        <span class="text-xs text-muted">or</span>
                        <div class="flex-1 h-px" style="background:#FFE566;"></div>
                    </div> --}}

                    {{-- Google button --}}
                    {{-- <a href="{{ route('booking.google.store') }}"
    onclick="event.preventDefault(); document.getElementById('google-booking-form').submit();"
                        class="w-full flex items-center justify-center gap-3 py-3.5 rounded-2xl border text-sm font-medium transition-all active:scale-95 hover:shadow-md"
                        style="border-color:#FFE566; background:#FFFFFF; color:#3D3530;">
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                            <path fill="none" d="M0 0h48v48H0z"/>
                        </svg>
                        Continue with Google
                    </a> --}}

                    {{-- Register link --}}
                    {{-- <p class="text-center text-xs text-muted">
                        Don’t have an account?
                        <span class="font-semibold ml-1" style="color:#B89200;">
                            Enter an email + password and we’ll create one automatically.
                        </span>
                    </p> --}}
                </form>

                {{-- Hidden Google booking form --}}
                <form method="POST" action="{{ route('booking.google.store') }}" id="google-booking-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="room_type" id="google-room_type" value="{{ $roomName ?? '' }}">
                    <input type="hidden" name="check_in" id="google-check_in" value="{{ old('check_in') }}">
                    <input type="hidden" name="check_out" id="google-check_out" value="{{ old('check_out') }}">
                    <input type="hidden" name="number_of_guests" id="google-guests" value="{{ old('number_of_guests', 1) }}">
                    <input type="hidden" name="nights" id="google-nights" value="{{ old('nights', 0) }}">
                    <input type="hidden" name="floor_level" id="google-floor" value="{{ old('floor_level', 'Floor 1') }}">
                    <input type="hidden" name="ambiance" id="google-ambiance" value="{{ old('ambiance', 'Regular Room') }}">
                    <input type="hidden" name="food_package" id="google-food" value="{{ old('food_package', 'No Food') }}">
                    <input type="hidden" name="room_price" value="{{ $price }}">
                    <input type="hidden" name="micro_pricing_amount" id="google-addons" value="{{ old('micro_pricing_amount', 0) }}">
                    <input type="hidden" name="total_price" id="google-total" value="{{ old('total_price', $price) }}">
                </form>
            </div>
        </div>
    </div>

    {{-- MOBILE STICKY BAR --}}
    <div class="lg:hidden sticky-bar fixed bottom-0 left-0 right-0 px-5 py-4 z-30 border-t"
        style="background:#FFFDF0; border-color:#FFE566;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-muted">Total</p>
                <p class="font-display text-2xl font-semibold price-bump" style="color:#B89200;" id="total-price-mobile">
                    ₱{{ number_format($price) }}
                </p>
            </div>
            <button type="button" onclick="openSignInModal()"
                class="px-7 py-3.5 rounded-2xl font-medium text-sm active:scale-95 transition-all flex items-center gap-2"
                style="background:#FFD000; color:#1C1C1E;">
                <i class="fas fa-calendar-check text-xs"></i>
                Book Now
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fecha@4.2.3/dist/fecha.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hotel-datepicker@4.12.2/dist/js/hotel-datepicker.min.js"></script>

    <script>
        const BASE_PRICE = {{ $price }};
        const disabledDates = @json($disabledDates);

        // important: use food_package key, not food
        const groupAddons = {
            ambiance: 0,
            food_package: 0
        };

        let selectedNights = 0;

        function parsePeso(text) {
            return Number(String(text).replace(/[₱,\s]/g, '')) || 0;
        }

        function recalcTotal() {
            const addonPerNight = (groupAddons.ambiance || 0) + (groupAddons.food_package || 0);
            const effectiveNights = selectedNights > 0 ? selectedNights : 1;
            const total = (BASE_PRICE + addonPerNight) * effectiveNights;

            document.getElementById('addon-display').textContent = '₱' + addonPerNight.toLocaleString();
            document.getElementById('nights-display').textContent = selectedNights > 0 ? selectedNights : '—';

            document.getElementById('input-addons').value = addonPerNight;
            document.getElementById('input-total').value = total;
            document.getElementById('input-nights').value = selectedNights;

            const formatted = '₱' + total.toLocaleString();

            ['total-price', 'total-price-mobile'].forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                el.textContent = formatted;
                el.classList.add('bump');
                setTimeout(() => el.classList.remove('bump'), 220);
            });
        }

        function syncBookingToModalAndGoogle() {
            const map = {
                'check_in': 'modal-check_in',
                'check_out': 'modal-check_out',
                'input-guests': 'modal-guests',
                'input-nights': 'modal-nights',
                'input-floor': 'modal-floor',
                'input-ambiance': 'modal-ambiance',
                'input-food': 'modal-food',
                'input-addons': 'modal-addons',
                'input-total': 'modal-total',
            };

            Object.entries(map).forEach(([srcId, dstId]) => {
                const src = document.getElementById(srcId);
                const dst = document.getElementById(dstId);
                if (src && dst) dst.value = src.value;
            });

            const googleMap = {
                'check_in': 'google-check_in',
                'check_out': 'google-check_out',
                'input-guests': 'google-guests',
                'input-nights': 'google-nights',
                'input-floor': 'google-floor',
                'input-ambiance': 'google-ambiance',
                'input-food': 'google-food',
                'input-addons': 'google-addons',
                'input-total': 'google-total',
            };

            Object.entries(googleMap).forEach(([srcId, dstId]) => {
                const src = document.getElementById(srcId);
                const dst = document.getElementById(dstId);
                if (src && dst) dst.value = src.value;
            });

            const totalText = document.getElementById('total-price').textContent;
            const modalSummaryTotal = document.getElementById('modal-summary-total');
            if (modalSummaryTotal) modalSummaryTotal.textContent = totalText;
        }

        // datepicker
        const datepicker = new HotelDatepicker(document.getElementById('booking_range'), {
            format: 'MMM D, YYYY',
            minNights: 1,
            disabledDates: disabledDates,
            clearButton: true,
            selectForward: true,
            onSelectRange() {
                const value = document.getElementById('booking_range').value.trim();
                const parts = value.split(' - ');

                if (parts.length !== 2) {
                    selectedNights = 0;
                    document.getElementById('check_in').value = '';
                    document.getElementById('check_out').value = '';
                    document.getElementById('input-nights').value = 0;
                    document.getElementById('nights-badge').classList.add('hidden');
                    syncBookingToModalAndGoogle();
                    recalcTotal();
                    return;
                }

                const start = new Date(parts[0]);
                const end = new Date(parts[1]);

                document.getElementById('check_in').value = fecha.format(start, 'YYYY-MM-DD');
                document.getElementById('check_out').value = fecha.format(end, 'YYYY-MM-DD');

                const msPerDay = 1000 * 60 * 60 * 24;
                selectedNights = Math.round((end - start) / msPerDay);
                document.getElementById('input-nights').value = selectedNights;

                const badge = document.getElementById('nights-badge');
                document.getElementById('nights-label').textContent =
                    selectedNights + ' night' + (selectedNights !== 1 ? 's' : '');
                badge.classList.remove('hidden');

                syncBookingToModalAndGoogle();
                recalcTotal();
            }
        });

        // initialize from old values if present
        document.addEventListener('DOMContentLoaded', function () {
            const oldCheckIn = document.getElementById('check_in').value;
            const oldCheckOut = document.getElementById('check_out').value;
            const oldNights = parseInt(document.getElementById('input-nights').value || 0);

            // set selected add-on values from old input
            const oldAmbiance = document.getElementById('input-ambiance').value;
            const oldFood = document.getElementById('input-food').value;

            const ambiancePrices = {
                'Regular Room': 0,
                'Cozy Ambiance': 500,
                'Romantic Ambiance': 1000
            };

            const foodPrices = {
                'No Food': 0,
                'Cozy Dinner for Family': 1500,
                'Romantic Dinner': 1500
            };

            groupAddons.ambiance = ambiancePrices[oldAmbiance] ?? 0;
            groupAddons.food_package = foodPrices[oldFood] ?? 0;

            if (oldCheckIn && oldCheckOut && oldNights > 0) {
                selectedNights = oldNights;
                document.getElementById('nights-label').textContent =
                    selectedNights + ' night' + (selectedNights !== 1 ? 's' : '');
                document.getElementById('nights-badge').classList.remove('hidden');
            }

            syncBookingToModalAndGoogle();
            recalcTotal();
        });

        // option row selection
        document.querySelectorAll('.option-row').forEach(row => {
            row.addEventListener('click', function () {
                const group = this.dataset.group;
                const price = parseInt(this.dataset.price) || 0;

                document.querySelectorAll(`.option-row[data-group="${group}"]`).forEach(r => {
                    r.classList.remove('selected');

                    const badge = r.querySelector('.badge');
                    const badgePrice = parseInt(r.dataset.price) || 0;

                    badge.style.cssText = 'background:#FFF8D6; color:#7A6E68;';

                    if (group === 'floor_level') {
                        badge.textContent = 'Free';
                    } else if (group === 'ambiance' && badgePrice === 0) {
                        badge.textContent = 'Base';
                    } else if (group === 'food_package' && badgePrice === 0) {
                        badge.textContent = 'Free';
                    } else {
                        badge.textContent = '+ ₱' + badgePrice.toLocaleString();
                    }
                });

                this.classList.add('selected');
                const badge = this.querySelector('.badge');

                if (group === 'floor_level') {
                    badge.textContent = 'Free';
                } else if (group === 'ambiance' && price === 0) {
                    badge.textContent = 'Base';
                } else if (group === 'food_package' && price === 0) {
                    badge.textContent = 'Free';
                } else {
                    badge.textContent = '+ ₱' + price.toLocaleString();
                }

                badge.style.cssText = 'background:#FFF3A3; color:#B89200;';

                const label = this.querySelector('div span:last-child').textContent.trim();

                if (group === 'floor_level') {
                    document.getElementById('input-floor').value = label;
                } else if (group === 'ambiance') {
                    document.getElementById('input-ambiance').value = label;
                    groupAddons.ambiance = price;
                } else if (group === 'food_package') {
                    document.getElementById('input-food').value = label;
                    groupAddons.food_package = price;
                }

                syncBookingToModalAndGoogle();
                recalcTotal();
            });
        });

        // guest stepper
        function stepGuests(delta) {
            const input = document.getElementById('pax_count');
            const max = parseInt(input.max) || 10;
            let val = parseInt(input.value) || 1;

            val = Math.min(max, Math.max(1, val + delta));
            input.value = val;
            document.getElementById('input-guests').value = val;

            syncBookingToModalAndGoogle();
        }

        document.getElementById('pax_count').addEventListener('change', function () {
            let val = parseInt(this.value) || 1;
            const max = parseInt(this.max) || 10;
            val = Math.min(max, Math.max(1, val));
            this.value = val;
            document.getElementById('input-guests').value = val;
            syncBookingToModalAndGoogle();
        });

        // modal controls
        function openSignInModal() {
            syncBookingToModalAndGoogle();

            const overlay = document.getElementById('signin-overlay');
            const modal = document.getElementById('signin-modal');

            overlay.classList.remove('hidden');
            overlay.classList.add('flex');

            requestAnimationFrame(() => {
                modal.style.transform = 'translateY(0)';
                modal.style.opacity = '1';
            });

            setTimeout(() => {
                const emailInput = document.getElementById('modal-email');
                if (emailInput) emailInput.focus();
            }, 340);
        }

        function closeSignInModal() {
            const overlay = document.getElementById('signin-overlay');
            const modal = document.getElementById('signin-modal');

            modal.style.transform = 'translateY(24px)';
            modal.style.opacity = '0';

            setTimeout(() => {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }, 320);
        }

        document.getElementById('signin-overlay').addEventListener('click', function (e) {
            if (e.target === this) closeSignInModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSignInModal();
        });

        function togglePassword() {
            const input = document.getElementById('modal-password');
            const icon = document.getElementById('eye-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash text-xs';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye text-xs';
            }
        }

        // modal file upload
        function showModalIdError(message) {
            const error = document.getElementById('modal-valid-id-error');
            error.querySelector('span').textContent = message;
            error.classList.remove('hidden');
        }

        function clearModalIdError() {
            const error = document.getElementById('modal-valid-id-error');
            error.querySelector('span').textContent = '';
            error.classList.add('hidden');
        }

        function clearModalIdUpload() {
            const input = document.getElementById('modal_valid_id');
            const previewWrap = document.getElementById('modal-valid-id-preview-wrap');
            const placeholder = document.getElementById('modal-valid-id-placeholder');
            const preview = document.getElementById('modal-valid-id-preview');
            const name = document.getElementById('modal-valid-id-name');

            input.value = '';
            preview.src = '';
            name.textContent = '';
            previewWrap.classList.add('hidden');
            placeholder.classList.remove('hidden');
            clearModalIdError();
        }

        function handleModalIdUpload(input) {
            const file = input.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                showModalIdError('Invalid file type. Please upload a JPG, PNG or PDF.');
                input.value = '';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                showModalIdError('File size exceeds 5MB limit. Please upload a smaller file.');
                input.value = '';
                return;
            }

            clearModalIdError();

            const previewWrap = document.getElementById('modal-valid-id-preview-wrap');
            const placeholder = document.getElementById('modal-valid-id-placeholder');
            const preview = document.getElementById('modal-valid-id-preview');
            const name = document.getElementById('modal-valid-id-name');

            name.textContent = file.name;
            previewWrap.classList.remove('hidden');
            placeholder.classList.add('hidden');

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
            }
        }

        // client-side validation before submit
        document.getElementById('signin-form').addEventListener('submit', function (e) {
            clearModalIdError();

            const email = document.getElementById('modal-email');
            const password = document.getElementById('modal-password');
            const checkIn = document.getElementById('modal-check_in').value;
            const checkOut = document.getElementById('modal-check_out').value;
            const nights = parseInt(document.getElementById('modal-nights').value || 0);

            let valid = true;

            if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                valid = false;
            }

            if (!password.value || password.value.length < 8) {
                valid = false;
            }

            if (!checkIn || !checkOut || nights < 1) {
                alert('Please select valid booking dates first.');
                valid = false;
            }

            const idInput = document.getElementById('modal_valid_id');
            if (idInput.files.length > 0) {
                const file = idInput.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];

                if (!validTypes.includes(file.type)) {
                    showModalIdError('Invalid file type. Please upload a JPG, PNG or PDF.');
                    valid = false;
                }

                if (file.size > 5 * 1024 * 1024) {
                    showModalIdError('File size exceeds 5MB limit. Please upload a smaller file.');
                    valid = false;
                }
            }

            if (!valid) {
                e.preventDefault();
                return;
            }

            const btn = document.getElementById('signin-submit');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Signing in…';
            btn.style.opacity = '0.75';
        });

        // reopen modal after validation errors
        @if ($errors->any())
            window.addEventListener('load', () => {
                openSignInModal();
            });
        @endif
    </script>
</body>
</html>

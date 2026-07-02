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
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
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
                        display: ['Fraunces', 'serif'],
                        body:    ['DM Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    @include('components.devtools-protection')

    <style>
        * { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Fraunces', serif; }
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

        .wizard-step-pill {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            text-align: center;
            position: relative;
        }

        .wizard-pill {
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            border: 1px solid transparent;
            background: rgba(255,237,187,0.8);
            color: #6b5533;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            padding: 0;
        }

        .wizard-pill.active {
            background: #fff3a3;
            border-color: #d4a800;
            color: #3d3530;
            box-shadow: 0 0 0 4px rgba(212,168,0,0.12);
        }

        .wizard-pill.completed {
            background: #d4a800;
            border-color: #b89300;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(255, 216, 0, 0.16);
        }

        .wizard-step-pill.active .wizard-pill-label,
        .wizard-step-pill.completed .wizard-pill-label {
            color: #3d3530;
            font-weight: 700;
        }

        .wizard-pill-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #765a2a;
            max-width: 72px;
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

                @if ($errors->any())
                    <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        <p class="font-semibold text-sm mb-1">We couldn’t submit your booking because of these issues:</p>
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('customize.login.with.booking') }}" id="booking-wizard-form" class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #FFE566;" enctype="multipart/form-data">
                    @csrf
                    {{-- Hidden booking values on page side to save selection - MSMG --}}
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
                        <div class="px-6 py-5 bg-gradient-to-bl from-amber-300 to-amber-500">
                            <h2 class="font-display text-2xl font-semibold text-charcoal">Customize Your Stay</h2>
                            <p class="text-xs mt-1 font-light" style="color:rgba(28,28,30,0.55);">
                                Complete your booking in 4 simple steps.
                            </p>
                            <div class="grid grid-cols-4 gap-6 mt-5">
                                <div class="wizard-step-pill" data-step="1">
                                    <button type="button" class="wizard-pill active" onclick="goToStep(1)">
                                        <span class="wizard-pill-number">1</span>
                                    </button>
                                    <span class="wizard-pill-label">Options</span>
                                </div>
                                <div class="wizard-step-pill" data-step="2">
                                    <button type="button" class="wizard-pill" onclick="goToStep(2)">
                                        <span class="wizard-pill-number">2</span>
                                    </button>
                                    <span class="wizard-pill-label">Details</span>
                                </div>
                                <div class="wizard-step-pill" data-step="3">
                                    <button type="button" class="wizard-pill" onclick="goToStep(3)">
                                        <span class="wizard-pill-number">3</span>
                                    </button>
                                    <span class="wizard-pill-label">ID</span>
                                </div>
                                <div class="wizard-step-pill" data-step="4">
                                    <button type="button" class="wizard-pill" onclick="goToStep(4)">
                                        <span class="wizard-pill-number">4</span>
                                    </button>
                                    <span class="wizard-pill-label">Review</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-6 space-y-7">
                            <div class="wizard-step" data-step="1">
                                <div class="section-label mb-3">
                                    <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Step 1: Select your stay</span>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <div class="section-label mb-3">
                                            <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Guests</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="button" onclick="stepGuests(-1)" class="w-10 h-10 rounded-xl border flex items-center justify-center text-lg font-semibold text-warm transition hover:bg-yellow-50" style="border-color:#FFE566;">−</button>
                                            <input type="number" id="pax_count" value="{{ old('number_of_guests', 1) }}" min="1" max="{{ $room->capacity ?? 10 }}" class="flex-1 border rounded-xl py-2.5 text-center text-sm font-semibold text-charcoal" style="border-color:#FFE566; background:#FFF8D6;">
                                            <button type="button" onclick="stepGuests(1)" class="w-10 h-10 rounded-xl border flex items-center justify-center text-lg font-semibold text-warm transition hover:bg-yellow-50" style="border-color:#FFE566;">+</button>
                                        </div>
                                        @error('number_of_guests')
                                            <p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>
                                        @enderror
                                    </div>

                                    <div>
                                        <div class="section-label mb-3">
                                            <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Dates</span>
                                        </div>
                                        @php
                                            $oldDateRange = '';
                                            if (old('check_in') && old('check_out')) {
                                                try {
                                                    $oldDateRange = \Carbon\Carbon::parse(old('check_in'))->format('M j, Y') . ' - ' . \Carbon\Carbon::parse(old('check_out'))->format('M j, Y');
                                                } catch (\Throwable $e) {
                                                    $oldDateRange = '';
                                                }
                                            }
                                        @endphp
                                        <input type="text" id="booking_range" placeholder="Select check-in → check-out" readonly value="{{ $oldDateRange }}" class="w-full border rounded-xl px-4 py-3 text-sm text-warm cursor-pointer" style="border-color:#FFE566; background:#FFF8D6;">
                                        <p id="nights-badge" class="hidden mt-2 text-xs font-medium" style="color:#B89200;"><i class="fas fa-moon mr-1"></i><span id="nights-label"></span></p>
                                        @error('check_in')<p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>@enderror
                                        @error('check_out')<p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>@enderror
                                        @error('nights')<p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>@enderror
                                    </div>

                                    <div>
                                        <div class="section-label mb-3"><span class="text-xs font-medium tracking-widest uppercase text-charcoal">Floor Level</span></div>
                                        <div class="space-y-2">
                                            @php $selectedFloor = old('floor_level', 'Floor 1'); @endphp
                                            @foreach (['Floor 1', 'Floor 2', 'Floor 4'] as $floor)
                                                <div class="option-row {{ $selectedFloor === $floor ? 'selected' : '' }} flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:{{ $selectedFloor === $floor ? '#D4A800' : '#FFE566' }};" data-group="floor_level" data-price="0">
                                                    <div class="flex items-center gap-3"><span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span><span class="text-sm {{ $selectedFloor === $floor ? 'font-medium' : '' }} text-warm">{{ $floor }}</span></div>
                                                    <span class="badge text-xs font-medium px-3 py-1 rounded-full" style="background:{{ $selectedFloor === $floor ? '#FFF3A3' : '#FFF8D6' }}; color:{{ $selectedFloor === $floor ? '#B89200' : '#7A6E68' }};">Free</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('floor_level')<p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>@enderror
                                    </div>

                                    <div>
                                        <div class="section-label mb-3"><span class="text-xs font-medium tracking-widest uppercase text-charcoal">Ambiance</span></div>
                                        <div class="space-y-2">
                                            @php $selectedAmbiance = old('ambiance', 'Regular Room'); @endphp
                                            @foreach ([['label' => 'Regular Room', 'price' => 0], ['label' => 'Cozy Ambiance', 'price' => 500], ['label' => 'Romantic Ambiance', 'price' => 1000]] as $ambiance)
                                                <div class="option-row {{ $selectedAmbiance === $ambiance['label'] ? 'selected' : '' }} flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:{{ $selectedAmbiance === $ambiance['label'] ? '#D4A800' : '#FFE566' }};" data-group="ambiance" data-price="{{ $ambiance['price'] }}">
                                                    <div class="flex items-center gap-3"><span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span><span class="text-sm {{ $selectedAmbiance === $ambiance['label'] ? 'font-medium' : '' }} text-warm">{{ $ambiance['label'] }}</span></div>
                                                    <span class="badge text-xs font-medium px-3 py-1 rounded-full" style="background:{{ $selectedAmbiance === $ambiance['label'] ? '#FFF3A3' : '#FFF8D6' }}; color:{{ $selectedAmbiance === $ambiance['label'] ? '#B89200' : '#7A6E68' }};">{{ $ambiance['price'] == 0 ? 'Base' : '+ ₱' . number_format($ambiance['price']) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('ambiance')<p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>@enderror
                                    </div>

                                    <div>
                                        <div class="section-label mb-3"><span class="text-xs font-medium tracking-widest uppercase text-charcoal">Food Package</span></div>
                                        <div class="space-y-2">
                                            @php $selectedFood = old('food_package', 'No Food'); @endphp
                                            @foreach ([['label' => 'No Food', 'price' => 0], ['label' => 'Cozy Dinner for Family', 'price' => 1500], ['label' => 'Romantic Dinner', 'price' => 1500]] as $food)
                                                <div class="option-row {{ $selectedFood === $food['label'] ? 'selected' : '' }} flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:{{ $selectedFood === $food['label'] ? '#D4A800' : '#FFE566' }};" data-group="food_package" data-price="{{ $food['price'] }}">
                                                    <div class="flex items-center gap-3"><span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span><span class="text-sm {{ $selectedFood === $food['label'] ? 'font-medium' : '' }} text-warm">{{ $food['label'] }}</span></div>
                                                    <span class="badge text-xs font-medium px-3 py-1 rounded-full" style="background:{{ $selectedFood === $food['label'] ? '#FFF3A3' : '#FFF8D6' }}; color:{{ $selectedFood === $food['label'] ? '#B89200' : '#7A6E68' }};">{{ $food['price'] == 0 ? 'Free' : '+ ₱' . number_format($food['price']) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('food_package')<p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>@enderror
                                    </div>
                                </div>

                                <div class="pt-5" style="border-top:1px solid #FFE566;">
                                    <div class="rounded-2xl p-4 space-y-2 mb-5" style="background:#FFF8D6; border:1px solid #FFE566;">
                                        <div class="flex justify-between text-sm"><span class="text-muted">Rate / night</span><span class="font-medium text-charcoal">₱{{ number_format($price) }}</span></div>
                                        <div class="flex justify-between text-sm"><span class="text-muted">Add-ons / night</span><span class="font-medium text-charcoal" id="addon-display">₱0</span></div>
                                        <div class="flex justify-between text-sm"><span class="text-muted">Nights</span><span class="font-medium text-charcoal" id="nights-display">—</span></div>
                                        <div class="pt-2 flex justify-between items-center" style="border-top:1px solid #FFE566;"><span class="text-xs tracking-widest uppercase text-muted">Total</span><span class="font-display text-2xl font-semibold price-bump" style="color:#B89200;" id="total-price">₱{{ number_format($price) }}</span></div>
                                    </div>
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-sm text-muted">Step 1 of 4</span>
                                        <button type="button" onclick="handleNextStep(1)" class="ml-auto py-3.5 px-6 rounded-2xl font-medium text-sm transition-all active:scale-95 flex items-center justify-center gap-2" style="background:#FFD000; color:#1C1C1E;">Continue to your details</button>
                                    </div>
                                    <p class="text-center text-xs text-muted/70 mt-3">Free cancellation up to 24 hours before check-in</p>
                                </div>
                            </div>

                            <div class="wizard-step hidden" data-step="2">
                                <div class="section-label mb-3"><span class="text-xs font-medium tracking-widest uppercase text-charcoal">Step 2: Your information</span></div>
                                <p class="text-sm text-muted">We only need this to keep your reservation secure and to create your account.</p>
                                <div class="space-y-4">
                                    <div class="mt-3">
                                        <label class="block text-xs font-medium tracking-widest uppercase mb-1.5" style="color:#7A6E68;">Name</label>
                                        <input type="text" name="name" id="wizard-name" value="{{ old('name') }}" placeholder="Your full name" class="w-full rounded-xl border px-4 py-3 text-sm text-warm" style="border-color:#FFE566; background:#FFF8D6;">
                                        @error('name')<p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium tracking-widest uppercase mb-1.5" style="color:#7A6E68;">Email Address</label>
                                        <input type="email" name="email" id="wizard-email" value="{{ old('email') }}" placeholder="you@email.com" required class="w-full rounded-xl border px-4 py-3 text-sm text-warm" style="border-color:#FFE566; background:#FFF8D6;">
                                        <p class="field-error hidden" id="wizard-email-error"></p>
                                        @error('email')<p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>@enderror
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5"><label class="block text-xs font-medium tracking-widest uppercase" style="color:#7A6E68;">Password</label><span class="text-xs text-muted">Minimum 8 characters</span></div>
                                        <input type="password" name="password" id="wizard-password" placeholder="••••••••" required class="w-full rounded-xl border px-4 py-3 text-sm text-warm" style="border-color:#FFE566; background:#FFF8D6;">
                                        <p class="field-error hidden" id="wizard-password-error"></p>
                                        @error('password')<p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>@enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium tracking-widest uppercase mb-1.5" style="color:#7A6E68;">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="wizard-password-confirmation" placeholder="••••••••" required class="w-full rounded-xl border px-4 py-3 text-sm text-warm" style="border-color:#FFE566; background:#FFF8D6;">
                                        <p class="field-error hidden" id="wizard-password-confirmation-error"></p>
                                    </div>
                                </div>
                                <div class="rounded-2xl p-4 bg-[#FFF8D6] border border-yellow-200 text-sm text-charcoal my-4">
                                    <p class="font-semibold mb-2">Why we ask for this</p>
                                    <p class="text-xs text-muted">Your information is used to secure your reservation, create your account, and make check-in faster. We store it securely and never share it without your consent.</p>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <button type="button" onclick="goToStep(1)" class="py-3.5 px-6 rounded-2xl font-medium text-sm transition-all active:scale-95" style="background:#FFFFFF; color:#1C1C1E; border:1px solid #FFE566;">Back</button>
                                    <button type="button" onclick="handleNextStep(2)" class="py-3.5 px-6 rounded-2xl font-medium text-sm transition-all active:scale-95" style="background:#FFD000; color:#1C1C1E;">Continue to ID upload</button>
                                </div>
                            </div>

                            <div class="wizard-step hidden" data-step="3">
                                <div class="section-label mb-3"><span class="text-xs font-medium tracking-widest uppercase text-charcoal">Step 3: Upload a valid ID</span></div>
                                <p class="text-sm text-muted">We verify your identity to protect your reservation and comply with hotel security requirements.</p>
                                <div class="rounded-2xl p-4 bg-[#FFF8D6] border border-yellow-200">
                                    <p class="font-semibold text-charcoal mb-2">Accepted documents</p>
                                    <ul class="list-disc pl-5 text-xs text-muted space-y-1"><li>Government-issued ID</li><li>Passport</li><li>Driver’s license</li></ul>
                                    <p class="mt-3 text-xs text-warm">Upload is encrypted and stored securely for verification only.</p>
                                </div>
                                <label for="valid_id_path" class="flex flex-col items-center justify-center gap-3 w-full rounded-xl border-2 border-dashed cursor-pointer transition-colors hover:bg-yellow-50" style="border-color:#FFE566; background:#FFF8D6; min-height:120px;">
                                    <div id="id-upload-placeholder" class="flex flex-col items-center gap-2 mt-2">
                                        <i class="fas fa-id-card text-xl" style="color:#D4A800;"></i>
                                        <span class="text-sm font-medium text-warm">Click or tap to upload a valid ID</span>
                                        <span class="text-xs text-muted">JPG, PNG or PDF · max 5MB</span>
                                    </div>
                                    <div id="id-upload-preview-wrap" class="hidden items-center gap-3">
                                        <img id="id-upload-preview" src="" alt="ID preview" class="w-16 h-12 object-cover rounded-md border" />
                                        <div class="flex flex-col items-start text-left"><span class="text-xs font-medium text-warm" id="id-upload-name"></span><button type="button" class="text-xs text-red-500 mt-1" onclick="clearIdUpload()">Remove</button></div>
                                    </div>
                                    <input type="file" name="valid_id_path" id="valid_id_path" accept="image/jpeg,image/png,application/pdf" class="hidden" onchange="handleIdUpload(this)">
                                </label>
                                <p class="hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="id-upload-error"><i class="fas fa-circle-exclamation" style="font-size:10px;"></i><span></span></p>
                                @error('valid_id_path')<p class="field-error"><i class="fas fa-circle-exclamation"></i><span>{{ $message }}</span></p>@enderror
                                <div class="flex items-center justify-between gap-3 mt-5">
                                    <button type="button" onclick="goToStep(2)" class="py-3.5 px-6 rounded-2xl font-medium text-sm transition-all active:scale-95" style="background:#FFFFFF; color:#1C1C1E; border:1px solid #FFE566;">Back</button>
                                    <button type="button" onclick="handleNextStep(3)" class="py-3.5 px-6 rounded-2xl font-medium text-sm transition-all active:scale-95" style="background:#FFD000; color:#1C1C1E;">Continue to review</button>
                                </div>
                            </div>

                            <div class="wizard-step hidden" data-step="4">
                                <div class="section-label mb-3"><span class="text-xs font-medium tracking-widest uppercase text-charcoal">Step 4: Review & confirm</span></div>
                                <p class="text-sm text-muted">Review your booking details and confirm your final total.</p>
                                <div class="rounded-2xl border border-yellow-100 bg-[#FFF8D6] p-4 space-y-3">
                                    <div class="flex justify-between text-sm text-muted"><span>Room</span><span id="summary-room">{{ $roomName ?? 'Room' }}</span></div>
                                    <div class="flex justify-between text-sm text-muted"><span>Dates</span><span id="summary-dates">—</span></div>
                                    <div class="flex justify-between text-sm text-muted"><span>Guests</span><span id="summary-guests">1</span></div>
                                    <div class="flex justify-between text-sm text-muted"><span>Floor</span><span id="summary-floor">Floor 1</span></div>
                                    <div class="flex justify-between text-sm text-muted"><span>Ambiance</span><span id="summary-ambiance">Regular Room</span></div>
                                    <div class="flex justify-between text-sm text-muted"><span>Food package</span><span id="summary-food">No Food</span></div>
                                    <div class="flex justify-between text-sm text-muted"><span>Rate / night</span><span id="summary-rate">₱{{ number_format($price) }}</span></div>
                                    <div class="flex justify-between text-sm text-muted"><span>Add-ons / night</span><span id="summary-addons">₱0</span></div>
                                    <div class="flex justify-between text-sm text-muted"><span>Nights</span><span id="summary-nights">—</span></div>
                                    <div class="pt-3 border-t border-yellow-200 flex justify-between items-center"><span class="text-xs uppercase tracking-[.18em] text-muted">Final total</span><span class="font-display text-2xl font-semibold text-charcoal" id="summary-total">₱{{ number_format($price) }}</span></div>
                                </div>
                                <div class="rounded-2xl p-4 bg-[#FFF8D6] border border-blue-200 text-sm text-charcoal my-4">
                                    <p class="font-semibold mb-2">Secure handling</p>
                                    <p class="text-xs text-muted">Your booking and ID are stored securely in our system and used only for reservation verification. We respect your privacy and protect your information.</p>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <button type="button" onclick="goToStep(3)" class="py-3.5 px-6 rounded-2xl font-medium text-sm transition-all active:scale-95" style="background:#FFFFFF; color:#1C1C1E; border:1px solid #FFE566;">Back</button>
                                    <button type="submit" class="py-3.5 px-6 rounded-2xl font-medium text-sm transition-all active:scale-95" style="background:#FFD000; color:#1C1C1E;">Confirm booking</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
            <button type="button" onclick="handleNextStep(1)"
                class="px-7 py-3.5 rounded-2xl font-medium text-sm active:scale-95 transition-all flex items-center gap-2"
                style="background:#FFD000; color:#1C1C1E;">
                <i class="fas fa-calendar-check text-xs"></i>
                Book Now
            </button>
        </div>
    </div>

    <div id="dates-required-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 px-4 py-6" onclick="if (event.target === this) closeDatesRequiredModal()"  role="dialog" aria-modal="true" aria-labelledby="dates-required-title">
    <div class="w-full max-w-md rounded-3xl bg-white border border-yellow-100 shadow-2xl overflow-hidden">
        <div class="flex items-start justify-between gap-4 p-5">
            <div>
                <h3 id="dates-required-title" class="text-lg font-semibold text-charcoal">Booking dates required</h3>
                <p class="mt-2 text-sm text-muted">Select your check-in and check-out dates to see pricing and continue.</p>
            </div>
            <button type="button" onclick="closeDatesRequiredModal()" aria-label="Close" class="text-xl text-warm leading-none">&times;</button>
        </div>
        <div class="flex justify-end border-t border-yellow-100 p-4">
            <button type="button" onclick="closeDatesRequiredModal(); focusDatePicker();" class="rounded-2xl bg-yellow-100 px-4 py-2 text-sm font-semibold text-charcoal">Got it</button>
        </div>
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

        function syncBookingToHiddenFields() {
            const map = {
                'check_in': 'check_in',
                'check_out': 'check_out',
                'input-guests': 'input-guests',
                'input-nights': 'input-nights',
                'input-floor': 'input-floor',
                'input-ambiance': 'input-ambiance',
                'input-food': 'input-food',
                'input-addons': 'input-addons',
                'input-total': 'input-total',
            };

            Object.entries(map).forEach(([srcId, dstId]) => {
                const src = document.getElementById(srcId);
                const dst = document.getElementById(dstId);
                if (src && dst) dst.value = src.value;
            });
        }

        function updateReviewSummary() {
            document.getElementById('summary-room').textContent = '{{ $roomName ?? 'Room' }}';
            document.getElementById('summary-dates').textContent =
                document.getElementById('booking_range').value || '—';
            document.getElementById('summary-guests').textContent = document.getElementById('input-guests').value || '1';
            document.getElementById('summary-floor').textContent = document.getElementById('input-floor').value || 'Floor 1';
            document.getElementById('summary-ambiance').textContent = document.getElementById('input-ambiance').value || 'Regular Room';
            document.getElementById('summary-food').textContent = document.getElementById('input-food').value || 'No Food';
            document.getElementById('summary-rate').textContent = '₱' + BASE_PRICE.toLocaleString();
            document.getElementById('summary-addons').textContent = '₱' + ((groupAddons.ambiance || 0) + (groupAddons.food_package || 0)).toLocaleString();
            document.getElementById('summary-nights').textContent = selectedNights > 0 ? selectedNights : '—';
            document.getElementById('summary-total').textContent = document.getElementById('total-price').textContent;
        }

        function setActiveStep(step) {
            document.querySelectorAll('.wizard-step').forEach(el => {
                el.classList.toggle('hidden', el.dataset.step !== String(step));
            });
            document.querySelectorAll('.wizard-step-pill').forEach(pill => {
                const pillStep = Number(pill.dataset.step);
                const button = pill.querySelector('.wizard-pill');
                const badge = pill.querySelector('.wizard-pill-number');
                const isActive = pillStep === step;
                const isComplete = pillStep < step;

                pill.classList.toggle('active', isActive);
                pill.classList.toggle('completed', isComplete);

                if (button) {
                    button.classList.toggle('active', isActive);
                    button.classList.toggle('completed', isComplete);
                }
                if (badge) {
                    badge.textContent = isComplete ? '✓' : pillStep;
                }
            });
        }

        function goToStep(step) {
            setActiveStep(step);
            if (step === 4) updateReviewSummary();
        }

        function handleNextStep(currentStep) {
            if (currentStep === 1) {
                const checkIn = document.getElementById('check_in').value;
                const checkOut = document.getElementById('check_out').value;
                const nights = parseInt(document.getElementById('input-nights').value || 0);
                if (!checkIn || !checkOut || nights < 1) {
                    openDatesRequiredModal();
                    return;
                }
            }
            if (currentStep === 2) {
                const email = document.getElementById('wizard-email');
                const password = document.getElementById('wizard-password');
                const confirmPassword = document.getElementById('wizard-password-confirmation');

                let hasError = false;
                document.querySelectorAll('#wizard-email-error, #wizard-password-error, #wizard-password-confirmation-error').forEach(el => el.classList.add('hidden'));

                if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                    const el = document.getElementById('wizard-email-error');
                    el.textContent = 'Enter a valid email address.';
                    el.classList.remove('hidden');
                    hasError = true;
                }
                if (!password.value || password.value.length < 8) {
                    const el = document.getElementById('wizard-password-error');
                    el.textContent = 'Use at least 8 characters.';
                    el.classList.remove('hidden');
                    hasError = true;
                }
                if (password.value !== confirmPassword.value) {
                    const el = document.getElementById('wizard-password-confirmation-error');
                    el.textContent = 'Passwords do not match.';
                    el.classList.remove('hidden');
                    hasError = true;
                }
                if (hasError) return;
            }
            if (currentStep === 3) {
                const idInput = document.getElementById('valid_id_path');
                if (!idInput || idInput.files.length === 0) {
                    const el = document.getElementById('id-upload-error');
                    el.querySelector('span').textContent = 'Please upload a valid ID to continue.';
                    el.classList.remove('hidden');
                    return;
                }
            }
            syncBookingToHiddenFields();
            goToStep(currentStep + 1);
        }

        function openDatesRequiredModal() {
            const modal = document.getElementById('dates-required-modal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDatesRequiredModal() {
            const modal = document.getElementById('dates-required-modal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function focusDatePicker() {
            const bookingRange = document.getElementById('booking_range');
            if (!bookingRange) return;
            bookingRange.focus();
            bookingRange.click();
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeDatesRequiredModal();
            }
        });

        // datepicker
        const datepicker = new HotelDatepicker(document.getElementById('booking_range'), {
            format: 'MMM D, YYYY',
            minNights: 1,
            // maxNights: 20,
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
                    syncBookingToHiddenFields();
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

                syncBookingToHiddenFields();
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

            syncBookingToHiddenFields();
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

                syncBookingToHiddenFields();
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

            syncBookingToHiddenFields();
        }

        document.getElementById('pax_count').addEventListener('change', function () {
            let val = parseInt(this.value) || 1;
            const max = parseInt(this.max) || 10;
            val = Math.min(max, Math.max(1, val));
            this.value = val;
            document.getElementById('input-guests').value = val;
            syncBookingToHiddenFields();
        });

        function showIdError(message) {
            const error = document.getElementById('id-upload-error');
            if (!error) return;
            error.querySelector('span').textContent = message;
            error.classList.remove('hidden');
        }

        function clearIdError() {
            const error = document.getElementById('id-upload-error');
            if (!error) return;
            error.querySelector('span').textContent = '';
            error.classList.add('hidden');
        }

        function clearIdUpload() {
            const input = document.getElementById('valid_id_path');
            const previewWrap = document.getElementById('id-upload-preview-wrap');
            const placeholder = document.getElementById('id-upload-placeholder');
            const preview = document.getElementById('id-upload-preview');
            const name = document.getElementById('id-upload-name');

            if (!input) return;
            input.value = '';
            if (preview) preview.src = '';
            if (name) name.textContent = '';
            if (previewWrap) previewWrap.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
            clearIdError();
        }

        function handleIdUpload(input) {
            const file = input.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                showIdError('Invalid file type. Please upload a JPG, PNG or PDF.');
                input.value = '';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                showIdError('File size exceeds 5MB limit. Please upload a smaller file.');
                input.value = '';
                return;
            }

            clearIdError();

            const previewWrap = document.getElementById('id-upload-preview-wrap');
            const placeholder = document.getElementById('id-upload-placeholder');
            const preview = document.getElementById('id-upload-preview');
            const name = document.getElementById('id-upload-name');

            if (name) name.textContent = file.name;
            if (previewWrap) previewWrap.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');

            if (file.type.startsWith('image/') && preview) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else if (preview) {
                preview.src = '';
            }
        }

        // reopen wizard after validation errors
        @if ($errors->any())
            window.addEventListener('load', () => {
                goToStep(1);
            });
        @endif
    </script>
</body>
</html>

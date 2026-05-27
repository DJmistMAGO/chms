<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $room->name ?? 'Customize Your Stay' }} | Caree Hotel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/images/chlogo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold:    { DEFAULT: '#FFD000', light: '#FFE566', dark: '#B89200', faint: '#FFFAE6' },
                        cream:   '#FFFDF0',
                        charcoal:'#1C1C1E',
                        warm:    '#3D3530',
                        muted:   '#7A6E68',
                        panel:   '#FFF8D6',
                        selected:'#FFF3A3',
                        selborder:'#D4A800',
                    },
                    fontFamily: {
                        display: ['Cormorant Garamond', 'serif'],
                        body:    ['DM Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        * { font-family: 'DM Sans', sans-serif; }
        h1,h2,h3,.font-display { font-family: 'Cormorant Garamond', serif; }

        /* Option row transitions */
        .option-row {
            cursor: pointer;
            transition: background 0.18s ease, border-color 0.18s ease;
        }
        .option-row.selected {
            background: #FFF3A3;
            border-color: #D4A800 !important;
        }
        .option-row:not(.selected):hover {
            background: #FFFAE6;
        }
        .option-row .dot {
            opacity: 0;
            transition: opacity 0.18s ease;
        }
        .option-row.selected .dot {
            opacity: 1;
            background: #D4A800;
        }

        /* Price tally animation */
        #total-price {
            transition: transform 0.2s ease;
        }
        #total-price.bump {
            transform: scale(1.15);
        }

        /* Sticky summary bar */
        .sticky-bar {
            box-shadow: 0 -8px 32px rgba(0,0,0,0.08);
        }

        /* Image zoom on hover */
        .room-img-wrap:hover img {
            transform: scale(1.04);
        }
        .room-img-wrap img {
            transition: transform 0.5s ease;
        }

        /* Section title decoration */
        .section-label {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #FFE566;
        }

        /* Input focus */
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #FFD000 !important;
            box-shadow: 0 0 0 3px rgba(255,208,0,0.18);
        }

        /* Fade-in on load */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(18px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up-1 { animation: fadeUp 0.5s ease both; }
        .fade-up-2 { animation: fadeUp 0.5s 0.08s ease both; }
        .fade-up-3 { animation: fadeUp 0.5s 0.16s ease both; }
    </style>
</head>

<body class="bg-cream text-warm font-body antialiased min-h-screen pb-32 lg:pb-0">

    {{-- ===== NAVBAR (slim) ===== --}}
    <nav class="sticky top-0 z-40 backdrop-blur border-b px-4 md:px-10 py-3 flex items-center gap-4" style="background:rgba(255,253,240,0.92); border-color:#FFE566;">
        <a href="{{ url()->previous() }}"
            class="flex items-center gap-2 text-sm font-medium text-warm hover:text-gold-dark transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to Rooms</span>
        </a>
        <div class="flex-1"></div>
        <img src="{{ asset('assets/images/chlogo.png') }}" class="w-10 opacity-80">
    </nav>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="max-w-6xl mx-auto px-4 md:px-8 py-6 md:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">

            {{-- ==============================
                 LEFT COLUMN — Room Info
                 ============================== --}}
            <div class="fade-up-1">

                {{-- Room Image --}}
                <div class="room-img-wrap relative rounded-2xl overflow-hidden mb-5 shadow-md" style="height: 280px; md:height: 340px;">
                    <img src="{{ asset($room->image ?? 'assets/images/pRoom.png') }}"
                        alt="{{ $room->name ?? 'Premium Deluxe Room' }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>

                    {{-- Room type badge --}}
                    <span class="absolute top-4 right-4 bg-charcoal text-cream text-xs font-medium px-4 py-1.5 rounded-full tracking-widest uppercase">
                        {{ $roomType }}
                    </span>

                    {{-- Price per night floating --}}
                    <div class="absolute bottom-4 left-4">
                        <p class="text-white/60 text-xs tracking-wide uppercase mb-0.5">Starting from</p>
                        <p class="font-display text-3xl font-semibold text-white">
                            ₱{{ number_format($price) }}
                            <span class="text-white/50 text-sm font-body font-light">/night</span>
                        </p>
                    </div>
                </div>

                {{-- Room Name & Details --}}
                <h1 class="font-display text-3xl md:text-4xl font-semibold text-charcoal leading-tight mb-4">
                    {{ $roomType }}
                </h1>

                {{-- Quick stats --}}
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
                    <p class="text-xs font-medium tracking-widest uppercase text-gold-dark mb-3">Amenities</p>
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

            {{-- ==============================
                RIGHT COLUMN — Customize
                ============================== --}}
            <div class="fade-up-2">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border: 1px solid #FFE566;">

                    {{-- Panel header --}}
                    <div class="px-6 py-5" style="background:#FFD000;">
                        <h2 class="font-display text-2xl font-semibold text-charcoal">Customize Your Stay</h2>
                        <p class="text-charcoal/50 text-xs mt-1 font-light">Select your preferences to see transparent pricing breakdown</p>
                    </div>

                    <div class="px-6 py-6 space-y-7">

                        {{-- Dates --}}
                                <div class="grid grid-cols-2 gap-3" >
                                    <div>
                                        <label class="block text-xs tracking-widest uppercase text-muted mb-1.5">Check-in</label>
                                        <input type="date" name="check_in" id="checkin"
                                            class="w-full rounded-xl px-4 py-3 text-sm text-warm transition-colors"
                                            style="border:1px solid #FFE566; background:#FFF8D6;"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-xs tracking-widest uppercase text-muted mb-1.5">Check-out</label>
                                        <input type="date" name="check_out" id="checkout"
                                            class="w-full rounded-xl px-4 py-3 text-sm text-warm transition-colors"
                                            style="border:1px solid #FFE566; background:#FFF8D6;"
                                            required>
                                    </div>
                                </div>

                        {{-- ---- FLOOR LEVEL ---- --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Floor Level</span>
                            </div>
                            <div class="space-y-2" id="floor-group">
                                <div class="option-row selected flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:#D4A800;"
                                    data-group="floor" data-price="0">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm font-medium text-warm">Floor 1</span>
                                    </div>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:#FFE566;"
                                    data-group="floor" data-price="0">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Floor 2</span>
                                    </div>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:#FFE566;"
                                    data-group="floor" data-price="0">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Floor 4</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ---- Ambiance ---- --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Ambiance</span>
                            </div>
                            <div class="space-y-2" id="room-group">
                                <div class="option-row selected flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:#D4A800;"
                                    data-group="room" data-price="0">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm font-medium text-warm">Regular Room</span>
                                    </div>
                                    <span class="text-xs font-medium px-3 py-1 rounded-full" style="background:#FFF3A3; color:#B89200;">Base</span>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:#FFE566;"
                                    data-group="room" data-price="500">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Cozy Ambiance</span>
                                    </div>
                                    <span class="text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">+ ₱500</span>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:#FFE566;"
                                    data-group="room" data-price="1000">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Romantic Ambiance</span>
                                    </div>
                                    <span class="text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">+ ₱1,000</span>
                                </div>
                            </div>
                        </div>

                        {{-- ---- FOOD PACKAGE ---- --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Food Package</span>
                            </div>
                            <div class="space-y-2" id="food-group">
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:#FFE566;"
                                    data-group="food" data-price="0">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">No Food</span>
                                    </div>
                                    <span class="text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">Free</span>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:#FFE566;"
                                    data-group="food" data-price="1500">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Cozy Dinner for Family</span>
                                    </div>
                                    <span class="text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">+ ₱1,500</span>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3" style="border-color:#FFE566;"
                                    data-group="food" data-price="1500">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Romantic Dinner</span>
                                    </div>
                                    <span class="text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">+ ₱1,500</span>
                                </div>
                            </div>
                        </div>

                        {{-- ---- EXTRA ---- --}}
                        {{-- <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Extra</span>
                            </div>
                            <div class="space-y-2" id="extra-group">

                                -- Bed Foam --
                                <div class="flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#FFE566; background:#FFFDF0;"
                                    data-extra="bed-foam" data-unit-price="200">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-bed text-xs" style="color:#D4A800;"></i>
                                        <div>
                                            <span class="text-sm font-medium text-warm block">Bed Foam</span>
                                            <span class="text-xs text-muted">₱200 / pc</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                            class="qty-btn minus w-7 h-7 rounded-lg flex items-center justify-center font-bold text-sm transition-all active:scale-90"
                                            style="background:#FFE566; color:#1C1C1E;"
                                            data-target="bed-foam">−</button>
                                        <span class="qty-display w-6 text-center text-sm font-semibold text-charcoal"
                                            id="qty-bed-foam">0</span>
                                        <button type="button"
                                            class="qty-btn plus w-7 h-7 rounded-lg flex items-center justify-center font-bold text-sm transition-all active:scale-90"
                                            style="background:#FFD000; color:#1C1C1E;"
                                            data-target="bed-foam">+</button>
                                        <span class="text-xs font-medium w-14 text-right" id="subtotal-bed-foam"
                                            style="color:#B89200;">₱0</span>
                                    </div>
                                </div>

                                - Extra Pillow
                                <div class="flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#FFE566; background:#FFFDF0;"
                                    data-extra="extra-pillow" data-unit-price="100">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-cloud text-xs" style="color:#D4A800;"></i>
                                        <div>
                                            <span class="text-sm font-medium text-warm block">Extra Pillow</span>
                                            <span class="text-xs text-muted">₱100 / pc</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                            class="qty-btn minus w-7 h-7 rounded-lg flex items-center justify-center font-bold text-sm transition-all active:scale-90"
                                            style="background:#FFE566; color:#1C1C1E;"
                                            data-target="extra-pillow">−</button>
                                        <span class="qty-display w-6 text-center text-sm font-semibold text-charcoal"
                                            id="qty-extra-pillow">0</span>
                                        <button type="button"
                                            class="qty-btn plus w-7 h-7 rounded-lg flex items-center justify-center font-bold text-sm transition-all active:scale-90"
                                            style="background:#FFD000; color:#1C1C1E;"
                                            data-target="extra-pillow">+</button>
                                        <span class="text-xs font-medium w-14 text-right" id="subtotal-extra-pillow"
                                            style="color:#B89200;">₱0</span>
                                    </div>
                                </div>

                                 Towel Set
                                <div class="flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#FFE566; background:#FFFDF0;"
                                    data-extra="towel-set" data-unit-price="150">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-hands-wash text-xs" style="color:#D4A800;"></i>
                                        <div>
                                            <span class="text-sm font-medium text-warm block">Towel Set</span>
                                            <span class="text-xs text-muted">₱150 / set</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                            class="qty-btn minus w-7 h-7 rounded-lg flex items-center justify-center font-bold text-sm transition-all active:scale-90"
                                            style="background:#FFE566; color:#1C1C1E;"
                                            data-target="towel-set">−</button>
                                        <span class="qty-display w-6 text-center text-sm font-semibold text-charcoal"
                                            id="qty-towel-set">0</span>
                                        <button type="button"
                                            class="qty-btn plus w-7 h-7 rounded-lg flex items-center justify-center font-bold text-sm transition-all active:scale-90"
                                            style="background:#FFD000; color:#1C1C1E;"
                                            data-target="towel-set">+</button>
                                        <span class="text-xs font-medium w-14 text-right" id="subtotal-towel-set"
                                            style="color:#B89200;">₱0</span>
                                    </div>
                                </div>

                            </div>
                        </div> --}}

                        {{-- DIVIDER --}}
                        <div class="pt-6" style="border-top:1px solid #FFE566;">
                            <p class="text-xs font-medium tracking-[0.25em] uppercase text-charcoal text-center mb-5">
                                Identity Verification
                            </p>

                            <form method="POST" action="{{ '#' }}" class="space-y-4">
                                @csrf

                                <input type="hidden" name="room_id" value="{{ $room->id ?? 1 }}">
                                <input type="hidden" name="floor" id="input-floor" value="1">
                                <input type="hidden" name="room" id="input-room" value="Street room">
                                <input type="hidden" name="ambiance" id="input-ambiance" value="Standard">
                                <input type="hidden" name="add_on_price" id="input-addon" value="0">
                                <input type="hidden" name="extras_price" id="input-extras" value="0">

                                {{-- Full Name --}}
                                <div>
                                    <label class="block text-xs tracking-widest uppercase text-muted mb-1.5">Full Name</label>
                                    <input type="text" name="full_name"
                                        placeholder="Enter your full name"
                                        class="w-full rounded-xl px-4 py-3 text-sm text-warm placeholder-muted/50 transition-colors"
                                        style="border:1px solid #FFE566; background:#FFF8D6;"
                                        required>
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-xs tracking-widest uppercase text-muted mb-1.5">Email Address</label>
                                    <input type="email" name="email"
                                        placeholder="you@email.com"
                                        class="w-full rounded-xl px-4 py-3 text-sm text-warm placeholder-muted/50 transition-colors"
                                        style="border:1px solid #FFE566; background:#FFF8D6;"
                                        required>
                                </div>

                                {{-- Guests --}}
                                <div>
                                    <label class="block text-xs tracking-widest uppercase text-muted mb-1.5">Number of Guests</label>
                                    <select name="guests"
                                        class="w-full rounded-xl px-4 py-3 text-sm text-warm transition-colors appearance-none"
                                        style="border:1px solid #FFE566; background:#FFF8D6;">
                                        <option value="1">1 Guest</option>
                                        <option value="2" selected>2 Guests</option>
                                        <option value="3">3 Guests</option>
                                        <option value="4">4 Guests</option>
                                    </select>
                                </div>

                                {{-- Price breakdown (desktop inline) --}}
                                <div class="hidden lg:block rounded-2xl p-4 space-y-2" style="background:#FFF8D6; border:1px solid #FFE566;">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-muted">Base Rate</span>
                                        <span class="font-medium text-charcoal">₱{{ number_format($price) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm" id="addon-row">
                                        <span class="text-muted">Add-ons</span>
                                        <span class="font-medium text-charcoal" id="addon-display">₱0</span>
                                    </div>
                                    <div class="pt-2 flex justify-between items-center" style="border-top:1px solid #FFE566;">
                                        <span class="text-xs tracking-widest uppercase text-muted">Total / Night</span>
                                        <span class="font-display text-2xl font-semibold" style="color:#B89200;" id="total-price">
                                            ₱{{ number_format($price) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <button type="submit"
                                    class="w-full py-4 rounded-2xl font-medium tracking-wide active:scale-95 transition-all flex items-center justify-center gap-2 text-sm"
                                    style="background:#FFD000; color:#1C1C1E;">
                                    <i class="fas fa-calendar-check text-xs" style="color:#1C1C1E;"></i>
                                    Confirm Reservation
                                </button>
                                <p class="text-center text-xs text-muted/60">Free cancellation up to 24 hours before check-in</p>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    {{-- ===== MOBILE STICKY BOTTOM BAR ===== --}}
    <div class="lg:hidden sticky-bar fixed bottom-0 left-0 right-0 px-5 py-4 z-30 border-t" style="background:#FFFDF0; border-color:#FFE566;">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-muted">Total / Night</p>
                <p class="font-display text-2xl font-semibold" style="color:#B89200;" id="total-price-mobile">
                    ₱{{ number_format($price) }}
                </p>
            </div>
            <button onclick="document.querySelector('[type=submit]').click()"
                class="px-7 py-3.5 rounded-2xl font-medium text-sm active:scale-95 transition-all flex items-center gap-2"
                style="background:#FFD000; color:#1C1C1E;">
                <i class="fas fa-calendar-check text-xs"></i>
                Book Now
            </button>
        </div>
    </div>


    <script>
        // ---- Base price & add-on state ----
        const BASE_PRICE = {{ $price}};
        const groupAddons = { floor: 0, room: 0, ambiance: 0 };

        // Extras state: key = data-extra value, value = quantity
        const extrasQty = { 'bed-foam': 0, 'extra-pillow': 0, 'towel-set': 0 };

        // ---- Option row selection (floor / room / ambiance) ----
        document.querySelectorAll('.option-row').forEach(row => {
            row.addEventListener('click', function () {
                const group = this.dataset.group;
                const price = parseInt(this.dataset.price) || 0;
                const label = this.querySelector('div span:last-child').textContent.trim();

                // Deselect all in group
                document.querySelectorAll(`.option-row[data-group="${group}"]`).forEach(r => {
                    r.classList.remove('selected');
                    const badge = r.querySelector('span:last-child');
                    const addonPrice = parseInt(r.dataset.price) || 0;

                    badge.className = 'text-xs font-medium text-muted px-3 py-1 rounded-full';
                    badge.style.cssText = 'background:#FFF8D6;';
                });

                // Select clicked row
                this.classList.add('selected');
                const badge = this.querySelector('span:last-child');

                badge.className = 'text-xs font-medium px-3 py-1 rounded-full';
                badge.style.cssText = 'background:#FFF3A3; color:#B89200;';

                groupAddons[group] = price;

                // Sync hidden inputs
                if (group === 'floor')   document.getElementById('input-floor').value   = label;
                if (group === 'room')    document.getElementById('input-room').value     = label;
                if (group === 'ambiance') document.getElementById('input-ambiance').value = label;

                updatePrice();
            });
        });

        // ---- Extras +/- buttons ----
        document.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const target   = this.dataset.target;
                const isPlus   = this.classList.contains('plus');
                const unitPrice = parseInt(
                    document.querySelector(`[data-extra="${target}"]`).dataset.unitPrice
                ) || 0;

                if (isPlus) {
                    extrasQty[target]++;
                } else {
                    if (extrasQty[target] > 0) extrasQty[target]--;
                }

                // Update qty display
                document.getElementById(`qty-${target}`).textContent = extrasQty[target];

                // Update subtotal label
                const subtotal = extrasQty[target] * unitPrice;
                document.getElementById(`subtotal-${target}`).textContent =
                    subtotal === 0 ? '₱0' : '₱' + subtotal.toLocaleString();

                updatePrice();
            });
        });

        // ---- Recalculate total ----
        function updatePrice() {
            const optionAddons = Object.values(groupAddons).reduce((a, b) => a + b, 0);

            const extrasTotal = Object.keys(extrasQty).reduce((sum, key) => {
                const unitPrice = parseInt(
                    document.querySelector(`[data-extra="${key}"]`)?.dataset.unitPrice
                ) || 0;
                return sum + extrasQty[key] * unitPrice;
            }, 0);

            const totalAddon = optionAddons + extrasTotal;
            const total      = BASE_PRICE + totalAddon;

            document.getElementById('addon-display').textContent = '₱' + totalAddon.toLocaleString();
            document.getElementById('input-addon').value   = optionAddons;
            document.getElementById('input-extras').value  = extrasTotal;

            const priceEl       = document.getElementById('total-price');
            const priceMobileEl = document.getElementById('total-price-mobile');
            priceEl.textContent       = '₱' + total.toLocaleString();
            priceMobileEl.textContent = '₱' + total.toLocaleString();

            priceEl.classList.add('bump');
            priceMobileEl.classList.add('bump');
            setTimeout(() => {
                priceEl.classList.remove('bump');
                priceMobileEl.classList.remove('bump');
            }, 220);
        }

        // ---- Default dates ----
        const today    = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        document.getElementById('checkin').value  = today.toISOString().split('T')[0];
        document.getElementById('checkout').value = tomorrow.toISOString().split('T')[0];

        document.getElementById('checkin').addEventListener('change', function () {
            const cin  = new Date(this.value);
            const cout = new Date(document.getElementById('checkout').value);
            if (cout <= cin) {
                const next = new Date(cin);
                next.setDate(cin.getDate() + 1);
                document.getElementById('checkout').value = next.toISOString().split('T')[0];
            }
            document.getElementById('checkout').min = this.value;
        });

        document.getElementById('checkout').min = today.toISOString().split('T')[0];
    </script>

</body>
</html>

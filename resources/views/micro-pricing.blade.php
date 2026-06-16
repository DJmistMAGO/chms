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

    <style>
        * { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Cormorant Garamond', serif; }

        /* Option rows */
        .option-row { cursor: pointer; transition: background 0.18s ease, border-color 0.18s ease; }
        .option-row.selected { background: #FFF3A3; border-color: #D4A800 !important; }
        .option-row:not(.selected):hover { background: #FFFAE6; }
        .option-row .dot { opacity: 0; transition: opacity 0.18s ease; }
        .option-row.selected .dot { opacity: 1; background: #D4A800; }

        /* Price bump animation */
        .price-bump { transition: transform 0.2s ease; }
        .price-bump.bump { transform: scale(1.15); }

        /* Image zoom */
        .room-img-wrap:hover img { transform: scale(1.04); }
        .room-img-wrap img { transition: transform 0.5s ease; }

        /* Section label with trailing line */
        .section-label { display: flex; align-items: center; gap: 10px; }
        .section-label::after { content: ''; flex: 1; height: 1px; background: #FFE566; }

        /* Input / select focus */
        input:focus, select:focus { outline: none; border-color: #FFD000 !important; box-shadow: 0 0 0 3px rgba(255,208,0,0.18); }

        /* Sticky mobile bar shadow */
        .sticky-bar { box-shadow: 0 -8px 32px rgba(0,0,0,0.08); }

        /* Page fade-in */
        @keyframes fadeUp { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
        .fade-up-1 { animation: fadeUp 0.5s ease both; }
        .fade-up-2 { animation: fadeUp 0.5s 0.08s ease both; }
    </style>
</head>

<body class="text-warm font-body antialiased min-h-screen pb-32 lg:pb-0" style="background:#FFFDF0;">

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-40 backdrop-blur border-b px-4 md:px-10 py-3 flex items-center gap-4"
        style="background:rgba(255,253,240,0.92); border-color:#FFE566;">
        <a href="{{ url()->previous() }}"
            class="flex items-center gap-2 text-sm font-medium text-warm hover:text-yellow-700 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to Rooms</span>
        </a>
        <div class="flex-1"></div>
        <img src="{{ asset('assets/images/chlogo.png') }}" class="w-10 opacity-80">
    </nav>

    {{-- MAIN --}}
    <div class="max-w-6xl mx-auto px-4 md:px-8 py-6 md:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">

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


                {{-- Hidden fields — names match migration columns exactly --}}
                <input type="hidden" name="room_type"             value="{{ $roomName ?? '' }}">
                <input type="hidden" name="check_in"              id="check_in">
                <input type="hidden" name="check_out"             id="check_out">
                <input type="hidden" name="number_of_guests"      id="input-guests"   value="1">
                <input type="hidden" name="nights"                id="input-nights"   value="0">
                <input type="hidden" name="floor_level"           id="input-floor"    value="Floor 1">
                <input type="hidden" name="ambiance"              id="input-ambiance" value="Regular Room">
                <input type="hidden" name="food_package"          id="input-food"     value="No Food">
                <input type="hidden" name="room_price"            value="{{ $price }}">
                <input type="hidden" name="micro_pricing_amount"  id="input-addons"   value="0">
                <input type="hidden" name="total_price"           id="input-total"    value="{{ $price }}">

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden" style="border:1px solid #FFE566;">

                    {{-- Header --}}
                    <div class="px-6 py-5" style="background:#FFD000;">
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
                                <input type="number" id="pax_count" name="guests"
                                    value="1" min="1" max="{{ $room->capacity ?? 10 }}"
                                    class="flex-1 border rounded-xl py-2.5 text-center text-sm font-semibold text-charcoal"
                                    style="border-color:#FFE566; background:#FFF8D6;">
                                <button type="button"
                                    onclick="stepGuests(1)"
                                    class="w-10 h-10 rounded-xl border flex items-center justify-center text-lg font-semibold text-warm transition hover:bg-yellow-50"
                                    style="border-color:#FFE566;">+</button>
                            </div>
                        </div>

                        {{-- DATES --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Dates</span>
                            </div>
                            <input type="text" id="booking_range"
                                placeholder="Select check-in → check-out"
                                readonly
                                class="w-full border rounded-xl px-4 py-3 text-sm text-warm cursor-pointer"
                                style="border-color:#FFE566; background:#FFF8D6;">
                            {{-- Night count badge --}}
                            <p id="nights-badge" class="hidden mt-2 text-xs font-medium" style="color:#B89200;">
                                <i class="fas fa-moon mr-1"></i>
                                <span id="nights-label"></span>
                            </p>
                        </div>

                        {{-- FLOOR LEVEL (free preference) --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Floor Level</span>
                            </div>
                            <div class="space-y-2">
                                <div class="option-row selected flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#D4A800;" data-group="floor_level" data-price="0">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm font-medium text-warm">Floor 1</span>
                                    </div>
                                    <span class="badge text-xs font-medium px-3 py-1 rounded-full" style="background:#FFF3A3; color:#B89200;">Free</span>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#FFE566;" data-group="floor_level" data-price="0">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Floor 2</span>
                                    </div>
                                    <span class="badge text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">Free</span>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#FFE566;" data-group="floor_level" data-price="0">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Floor 4</span>
                                    </div>
                                    <span class="badge text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">Free</span>
                                </div>
                            </div>
                        </div>

                        {{-- AMBIANCE --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Ambiance</span>
                            </div>
                            <div class="space-y-2">
                                <div class="option-row selected flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#D4A800;" data-group="ambiance" data-price="0">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm font-medium text-warm">Regular Room</span>
                                    </div>
                                    <span class="badge text-xs font-medium px-3 py-1 rounded-full" style="background:#FFF3A3; color:#B89200;">Base</span>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#FFE566;" data-group="ambiance" data-price="500">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Cozy Ambiance</span>
                                    </div>
                                    <span class="badge text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">+ ₱500</span>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#FFE566;" data-group="ambiance" data-price="1000">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Romantic Ambiance</span>
                                    </div>
                                    <span class="badge text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">+ ₱1,000</span>
                                </div>
                            </div>
                        </div>

                        {{-- FOOD PACKAGE --}}
                        <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Food Package</span>
                            </div>
                            <div class="space-y-2">
                                <div class="option-row selected flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#D4A800;" data-group="food_package" data-price="0">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm font-medium text-warm">No Food</span>
                                    </div>
                                    <span class="badge text-xs font-medium px-3 py-1 rounded-full" style="background:#FFF3A3; color:#B89200;">Free</span>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#FFE566;" data-group="food_package" data-price="1500">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Cozy Dinner for Family</span>
                                    </div>
                                    <span class="badge text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">+ ₱1,500</span>
                                </div>
                                <div class="option-row flex items-center justify-between border rounded-xl px-4 py-3"
                                    style="border-color:#FFE566;" data-group="food_package" data-price="1500">
                                    <div class="flex items-center gap-3">
                                        <span class="dot w-2 h-2 rounded-full flex-shrink-0" style="background:#D4A800;"></span>
                                        <span class="text-sm text-warm">Romantic Dinner</span>
                                    </div>
                                    <span class="badge text-xs font-medium text-muted px-3 py-1 rounded-full" style="background:#FFF8D6;">+ ₱1,500</span>
                                </div>
                            </div>
                        </div>

                        {{-- VALID ID UPLOAD --}}
                        {{-- <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Valid ID</span>
                            </div>
                            <label for="valid_id"
                                class="flex flex-col items-center justify-center gap-2 w-full rounded-xl border-2 border-dashed cursor-pointer transition-colors hover:bg-yellow-50"
                                style="border-color:#FFE566; background:#FFF8D6; min-height:96px;"
                                id="valid-id-label">
                                <i class="fas fa-id-card text-xl" style="color:#D4A800;"></i>
                                <span class="text-xs font-medium text-warm" id="valid-id-text">Click to upload a valid ID</span>
                                <span class="text-xs text-muted">JPG, PNG or PDF · max 5MB</span>
                                <input type="file" name="valid_id_path" id="valid_id"
                                    accept="image/jpeg,image/png,application/pdf"
                                    class="hidden" required
                                    onchange="handleIdUpload(this)">
                            </label>
                            <p class="hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="valid-id-error">
                                <i class="fas fa-circle-exclamation" style="font-size:10px;"></i>
                                <span></span>
                            </p>
                        </div> --}}

                        {{-- SPECIAL REQUESTS --}}
                        {{-- <div>
                            <div class="section-label mb-3">
                                <span class="text-xs font-medium tracking-widest uppercase text-charcoal">Special Requests</span>
                                <span class="text-xs text-muted ml-1">(optional)</span>
                            </div>
                            <textarea name="special_requests" rows="3"
                                placeholder="Any special requests or notes for your stay…"
                                class="w-full rounded-xl px-4 py-3 text-sm text-warm placeholder-muted/50 resize-none transition-colors"
                                style="border:1px solid #FFE566; background:#FFF8D6;"></textarea>
                        </div>  --}}

                        {{-- PRICE BREAKDOWN + SUBMIT --}}
                        <div class="pt-5" style="border-top:1px solid #FFE566;">

                            {{-- Breakdown card --}}
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
                            <p class="text-center text-xs text-muted/70 mt-3">Free cancellation up to 24 hours before check-in</p>
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
            <div class="px-7 pt-7 pb-5 flex items-start justify-between" style="background:#FFD000;">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <img src="{{ asset('assets/images/chlogo.png') }}" class="w-8 opacity-90">
                        <span class="text-xs font-medium tracking-widest uppercase text-charcoal/60">Caree Hotel</span>
                    </div>
                    <h3 class="font-display text-2xl font-semibold text-charcoal leading-tight">
                        Sign in to continue
                    </h3>
                    <p class="text-xs mt-1" style="color:rgba(28,28,30,0.5);">
                        Confirm your identity to complete the reservation
                    </p>
                </div>
                <button onclick="closeSignInModal()"
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
                <form method="POST" action="{{ route('login.with.booking') }}" id="signin-form" class="space-y-4" enctype="multipart/form-data">
                    @csrf

                    {{-- ── Booking hidden fields (mirrors the outer form) ── --}}
                    <input type="hidden" name="room_type"            value="{{ $roomName ?? '' }}">
                    <input type="hidden" name="check_in"             id="modal-check_in">
                    <input type="hidden" name="check_out"            id="modal-check_out">
                    <input type="hidden" name="number_of_guests"     id="modal-guests"    value="1">
                    <input type="hidden" name="nights"               id="modal-nights"    value="0">
                    <input type="hidden" name="floor_level"          id="modal-floor"     value="Floor 1">
                    <input type="hidden" name="ambiance"             id="modal-ambiance"  value="Regular Room">
                    <input type="hidden" name="food_package"         id="modal-food"      value="No Food">
                    <input type="hidden" name="room_price"           value="{{ $price }}">
                    <input type="hidden" name="micro_pricing_amount" id="modal-addons"    value="0">
                    <input type="hidden" name="total_price"          id="modal-total"     value="{{ $price }}">

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-medium tracking-widest uppercase mb-1.5" style="color:#7A6E68;">
                            Email Address
                        </label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5" style="color:#D4A800;">
                                <i class="fas fa-envelope text-xs"></i>
                            </span>
                            <input type="email" name="email" id="modal-email"
                                placeholder="you@email.com"
                                required autocomplete="email"
                                class="w-full rounded-xl pl-9 pr-4 py-3 text-sm text-warm placeholder-muted/50 transition-colors"
                                style="border:1px solid #FFE566; background:#FFF8D6;">
                        </div>
                        <p class="modal-error hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="email-error">
                            <i class="fas fa-circle-exclamation" style="font-size:10px;"></i>
                            <span></span>
                        </p>
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
                            <input type="password" name="password" id="modal-password"
                                placeholder="••••••••"
                                required autocomplete="current-password"
                                class="w-full rounded-xl pl-9 pr-10 py-3 text-sm text-warm placeholder-muted/50 transition-colors"
                                style="border:1px solid #FFE566; background:#FFF8D6;">
                            {{-- Toggle visibility --}}
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 transition-colors"
                                style="color:#D4A800;">
                                <i id="eye-icon" class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                        <p class="modal-error hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="password-error">
                            <i class="fas fa-circle-exclamation" style="font-size:10px;"></i>
                            <span></span>
                        </p>
                    </div>

                    {{-- Remember me --}}
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" name="remember" id="modal-remember"
                            class="w-4 h-4 rounded accent-yellow-400">
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
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-px" style="background:#FFE566;"></div>
                        <span class="text-xs text-muted">or</span>
                        <div class="flex-1 h-px" style="background:#FFE566;"></div>
                    </div>

                    {{-- Sign in with Google --}}
                    <a href=""
                        class="w-full flex items-center justify-center gap-3 py-3.5 rounded-2xl border text-sm font-medium transition-all active:scale-95 hover:shadow-md"
                        style="border-color:#FFE566; background:#FFFFFF; color:#3D3530;">
                        {{-- Google SVG logo --}}
                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                            <path fill="none" d="M0 0h48v48H0z"/>
                        </svg>
                        Continue with Google
                    </a>

                    {{-- Register link --}}
                    <p class="text-center text-xs text-muted">
                        Don't have an account?
                        <a href=""
                            class="font-semibold hover:underline ml-1" style="color:#B89200;">
                            Create one
                        </a>
                    </p>

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
        // ── Constants ──────────────────────────────────────
        const BASE_PRICE = {{ $price }};

        // Tracks the per-night add-on from ambiance + food
        const groupAddons = { ambiance: 0, food: 0 };

        // Tracks number of selected nights
        let selectedNights = 0;

        const disabledDates = @json($disabledDates);

        // ── Helpers ────────────────────────────────────────
        function recalcTotal() {
            const addonPerNight = groupAddons.ambiance + groupAddons.food;
            const ratePerNight  = BASE_PRICE + addonPerNight;
            const total         = ratePerNight * (selectedNights || 1);

            document.getElementById('addon-display').textContent  = '₱' + addonPerNight.toLocaleString();
            document.getElementById('nights-display').textContent = selectedNights > 0 ? selectedNights : '—';
            document.getElementById('input-addons').value         = addonPerNight;
            document.getElementById('input-total').value          = total;

            const formatted = '₱' + total.toLocaleString();
            ['total-price', 'total-price-mobile'].forEach(id => {
                const el = document.getElementById(id);
                el.textContent = formatted;
                el.classList.add('bump');
                setTimeout(() => el.classList.remove('bump'), 220);
            });
        }

        // ── Hotel Datepicker ───────────────────────────────



        const datepicker = new HotelDatepicker(document.getElementById('booking_range'), {
            format:        'MMM D, YYYY',
            minNights:     1,
            disabledDates: disabledDates,
            clearButton:   true,
            selectForward: true,
            onSelectRange() {
                const parts = document.getElementById('booking_range').value.split(' - ');
                if (parts.length !== 2) {
                    selectedNights = 0;
                    document.getElementById('check_in').value = fecha.format(start, 'YYYY-MM-DD');
                    document.getElementById('check_out').value =fecha.format(end, 'YYYY-MM-DD');
                    document.getElementById('nights-badge').classList.add('hidden');
                    recalcTotal();
                    return;
                }

                document.getElementById('check_in').value  = parts[0];
                document.getElementById('check_out').value = parts[1];

                // Calculate nights
                const msPerDay = 1000 * 60 * 60 * 24;
                const d1 = new Date(parts[0]);
                const d2 = new Date(parts[1]);
                selectedNights = Math.round((d2 - d1) / msPerDay);
                document.getElementById('input-nights').value = selectedNights;

                const badge = document.getElementById('nights-badge');
                document.getElementById('nights-label').textContent =
                    selectedNights + ' night' + (selectedNights !== 1 ? 's' : '');
                badge.classList.remove('hidden');

                recalcTotal();
            }
        });

        // ── Option rows (ambiance + food; floor is free) ───
        document.querySelectorAll('.option-row').forEach(row => {
            row.addEventListener('click', function () {
                const group = this.dataset.group;
                const price = parseInt(this.dataset.price) || 0;

                // Deselect siblings
                document.querySelectorAll(`.option-row[data-group="${group}"]`).forEach(r => {
                    r.classList.remove('selected');
                    const badge      = r.querySelector('.badge');
                    const badgePrice = parseInt(r.dataset.price) || 0;

                    if (group === 'floor_level') {
                        badge.textContent     = 'Free';
                        badge.style.cssText   = 'background:#FFF8D6; color:#7A6E68;';
                    } else if (group === 'food' && badgePrice === 0) {
                        badge.textContent     = 'Free';
                        badge.style.cssText   = 'background:#FFF8D6; color:#7A6E68;';
                    } else if (group === 'ambiance' && badgePrice === 0) {
                        badge.textContent     = 'Base';
                        badge.style.cssText   = 'background:#FFF8D6; color:#7A6E68;';
                    } else {
                        badge.textContent     = '+ ₱' + badgePrice.toLocaleString();
                        badge.style.cssText   = 'background:#FFF8D6; color:#7A6E68;';
                    }
                });

                // Select this row
                this.classList.add('selected');
                const badge = this.querySelector('.badge');
                if (group === 'floor_level') {
                    badge.textContent   = 'Free';
                } else if ((group === 'food_package' || group === 'ambiance') && price === 0) {
                    badge.textContent   = group === 'ambiance' ? 'Base' : 'Free';
                } else {
                    badge.textContent   = '+ ₱' + price.toLocaleString();
                }
                badge.style.cssText = 'background:#FFF3A3; color:#B89200;';

                // Update groupAddons (floor never adds cost)
                if (group !== 'floor_level') groupAddons[group] = price;

                // Sync hidden input label
                const label = this.querySelector('div span:last-child').textContent.trim();
                if (group === 'floor_level')    document.getElementById('input-floor').value    = label;
                if (group === 'ambiance') document.getElementById('input-ambiance').value = label;
                if (group === 'food_package')     document.getElementById('input-food').value      = label;

                recalcTotal();
            });
        });

        // ── Guest stepper ──────────────────────────────────
        function stepGuests(delta) {
            const input = document.getElementById('pax_count');
            const max   = parseInt(input.max) || 10;
            let val     = parseInt(input.value) || 1;
            val = Math.min(max, Math.max(1, val + delta));
            input.value = val;
            document.getElementById('input-guests').value = val;
        }

        // Also sync on direct input change
        document.getElementById('pax_count').addEventListener('change', function () {
            document.getElementById('input-guests').value = this.value;
        });

        // // ── Valid ID upload preview ────────────────────────
        // function handleIdUpload(input) {
        //     const label   = document.getElementById('valid-id-text');
        //     const errEl   = document.getElementById('valid-id-error');
        //     const file    = input.files[0];

        //     errEl.classList.add('hidden');

        //     if (!file) { label.textContent = 'Click to upload a valid ID'; return; }

        //     // 5MB limit
        //     if (file.size > 5 * 1024 * 1024) {
        //         errEl.querySelector('span').textContent = 'File is too large. Maximum size is 5MB.';
        //         errEl.classList.remove('hidden');
        //         input.value = '';
        //         label.textContent = 'Click to upload a valid ID';
        //         return;
        //     }

        //     label.textContent = '✓ ' + file.name;
        //     document.getElementById('valid-id-label').style.borderColor = '#D4A800';
        // }

        // ── Sign-in Modal ──────────────────────────────────
        function openSignInModal() {
            const overlay = document.getElementById('signin-overlay');
            const modal   = document.getElementById('signin-modal');

            // ── Sync every live booking value into the modal form ──
            const syncMap = {
                'check_in':             'modal-check_in',
                'check_out':            'modal-check_out',
                'input-guests':         'modal-guests',
                'input-nights':         'modal-nights',
                'input-floor':          'modal-floor',
                'input-ambiance':       'modal-ambiance',
                'input-food':           'modal-food',
                'input-addons':         'modal-addons',
                'input-total':          'modal-total',
            };
            Object.entries(syncMap).forEach(([srcId, dstId]) => {
                const src = document.getElementById(srcId);
                const dst = document.getElementById(dstId);
                if (src && dst) dst.value = src.value;
            });

            // Sync live total into the summary strip
            document.getElementById('modal-summary-total').textContent =
                document.getElementById('total-price').textContent;

            overlay.classList.remove('hidden');
            overlay.classList.add('flex');

            requestAnimationFrame(() => {
                modal.style.transform = 'translateY(0)';
                modal.style.opacity   = '1';
            });

            setTimeout(() => document.getElementById('modal-email').focus(), 340);
        }

        function closeSignInModal() {
            const overlay = document.getElementById('signin-overlay');
            const modal   = document.getElementById('signin-modal');

            modal.style.transform = 'translateY(24px)';
            modal.style.opacity   = '0';

            setTimeout(() => {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }, 320);
        }

        // Close on backdrop click
        document.getElementById('signin-overlay').addEventListener('click', function (e) {
            if (e.target === this) closeSignInModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeSignInModal();
        });

        // Toggle password visibility
        function togglePassword() {
            const input = document.getElementById('modal-password');
            const icon  = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type     = 'text';
                icon.className = 'fas fa-eye-slash text-xs';
            } else {
                input.type     = 'password';
                icon.className = 'fas fa-eye text-xs';
            }
        }

        // Client-side validation + loading state on submit
        document.getElementById('signin-form').addEventListener('submit', function (e) {
            let valid = true;

            const email    = document.getElementById('modal-email');
            const password = document.getElementById('modal-password');
            const emailErr = document.getElementById('email-error');
            const passErr  = document.getElementById('password-error');

            // Reset errors
            [emailErr, passErr].forEach(el => el.classList.add('hidden'));

            if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                emailErr.querySelector('span').textContent = 'Please enter a valid email address.';
                emailErr.classList.remove('hidden');
                valid = false;
            }
            if (!password.value) {
                passErr.querySelector('span').textContent = 'Password is required.';
                passErr.classList.remove('hidden');
                valid = false;
            }

            if (!valid) { e.preventDefault(); return; }

            // Loading state
            const btn     = document.getElementById('signin-submit');
            btn.disabled  = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i> Signing in…';
            btn.style.opacity = '0.75';
        });
    </script>

</body>
</html>

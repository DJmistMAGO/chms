<div id="auth-wizard-root">

    @if ($errors->any())
        <div class="mb-4 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-red-300">
            <p class="font-semibold text-sm mb-1">We couldn’t submit your booking:</p>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="auth-wizard-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="room_type" value="{{ $roomName }}">
        <input type="hidden" name="room_type_slug" value="{{ $roomType }}">
        <input type="hidden" name="check_in" id="aw-check_in" value="">
        <input type="hidden" name="check_out" id="aw-check_out" value="">
        <input type="hidden" name="number_of_guests" id="aw-input-guests" value="1">
        <input type="hidden" name="nights" id="aw-input-nights" value="0">
        <input type="hidden" name="floor_level" id="aw-input-floor" value="Floor 1">
        <input type="hidden" name="ambiance" id="aw-input-ambiance" value="Regular Room">
        <input type="hidden" name="food_package" id="aw-input-food" value="No Food">
        <input type="hidden" name="room_price" value="{{ $price }}">
        <input type="hidden" name="micro_pricing_amount" id="aw-input-addons" value="0">
        <input type="hidden" name="total_price" id="aw-input-total" value="{{ $price }}">

        {{-- Slim progress bar (replaces the two big circular step pills) --}}
        <div class="mb-5">
            <div class="flex items-center gap-2">
                <div class="aw-step-pill flex-1" data-step="1">
                    <div class="aw-pill-track h-1.5 rounded-full bg-amber-400"></div>
                </div>
                <div class="aw-step-pill flex-1" data-step="2">
                    <div class="aw-pill-track h-1.5 rounded-full bg-white/10"></div>
                </div>
            </div>
            <div class="mt-1.5 flex justify-between text-[10px] font-medium uppercase tracking-widest text-slate-500">
                <span id="aw-step-label-1" class="text-amber-400">1. Options</span>
                <span id="aw-step-label-2">2. Review</span>
            </div>
        </div>

        {{-- STEP 1: OPTIONS --}}
        <div class="aw-step" data-step="1">
            <div class="space-y-4">

                {{-- Guests and Dates stacked vertically --}}
                <div class="space-y-3">
                    <div>
                        <label class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-1.5">Guests</label>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="awStepGuests(-1)" class="w-9 h-9 shrink-0 rounded-lg border border-white/10 text-base font-semibold text-white hover:bg-white/5">−</button>
                            <input type="number" id="aw-pax_count" value="1" min="1" max="{{ $room->capacity }}" class="w-full rounded-lg border border-white/10 bg-white/5 py-2 text-center text-sm font-semibold text-white">
                            <button type="button" onclick="awStepGuests(1)" class="w-9 h-9 shrink-0 rounded-lg border border-white/10 text-base font-semibold text-white hover:bg-white/5">+</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-1.5">
                            Dates
                            <span id="aw-nights-label" class="hidden normal-case tracking-normal text-amber-400 font-medium">· <span id="aw-nights-text"></span></span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs"></i>
                            <input type="text" id="aw-booking_range" placeholder="Check-in → check-out" readonly
                                class="w-full rounded-lg border border-white/10 bg-white/5 pl-9 pr-3 py-2 text-sm text-white cursor-pointer">
                        </div>
                    </div>
                </div>

                {{-- Compact chip grids instead of stacked full-width rows --}}
                <div>
                    <label class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-1.5">
                        <i class="fas fa-layer-group text-[10px]"></i> Floor Level
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach (['Floor 1', 'Floor 2', 'Floor 4'] as $i => $floor)
                            <div class="aw-option-row aw-chip {{ $i === 0 ? 'aw-selected' : '' }}"
                                data-group="floor_level" data-price="0" data-label="{{ $floor }}">
                                <span class="aw-chip-label">{{ $floor }}</span>
                                <span class="aw-badge">Free</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-1.5">
                        <i class="fas fa-heart text-[10px]"></i> Ambiance
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ([['label' => 'Regular Room', 'price' => 0], ['label' => 'Cozy Ambiance', 'price' => 500], ['label' => 'Romantic Ambiance', 'price' => 1000]] as $i => $ambiance)
                            <div class="aw-option-row aw-chip {{ $i === 0 ? 'aw-selected' : '' }}"
                                data-group="ambiance" data-price="{{ $ambiance['price'] }}" data-label="{{ $ambiance['label'] }}">
                                <span class="aw-chip-label">{{ $ambiance['label'] }}</span>
                                <span class="aw-badge">{{ $ambiance['price'] == 0 ? 'Base' : '+₱' . number_format($ambiance['price']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-widest text-slate-400 mb-1.5">
                        <i class="fas fa-utensils text-[10px]"></i> Food Package
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ([['label' => 'No Food', 'price' => 0], ['label' => 'Cozy Dinner for Family', 'price' => 1500], ['label' => 'Romantic Dinner', 'price' => 1500]] as $i => $food)
                            <div class="aw-option-row aw-chip {{ $i === 0 ? 'aw-selected' : '' }}"
                                data-group="food_package" data-price="{{ $food['price'] }}" data-label="{{ $food['label'] }}">
                                <span class="aw-chip-label">{{ $food['label'] }}</span>
                                <span class="aw-badge">{{ $food['price'] == 0 ? 'Free' : '+₱' . number_format($food['price']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sticky compact price bar so the total is always visible without adding scroll height --}}
            <div class="aw-price-bar mt-5 rounded-2xl border border-white/10 bg-slate-900/80 backdrop-blur px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="text-[11px] text-slate-400 leading-tight">
                        <span id="aw-nights-display">—</span> night(s) · ₱{{ number_format($price) }} + <span id="aw-addon-display">₱0</span> add-ons
                    </div>
                    <span class="text-lg font-semibold text-amber-400" id="aw-total-price">₱{{ number_format($price) }}</span>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between gap-3">
                <button type="button" onclick="backToRoomPicker()" class="rounded-xl border border-white/10 px-4 py-2.5 text-sm font-medium text-slate-300 hover:bg-white/5">Back to rooms</button>
                <button type="button" onclick="awNextStep(1)" class="rounded-xl bg-amber-400 px-5 py-2.5 text-sm font-semibold text-slate-900 hover:bg-amber-300">Continue to review</button>
            </div>
        </div>

        {{-- STEP 2: REVIEW --}}
        <div class="aw-step hidden" data-step="2">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 text-sm">
                    <span class="text-slate-400">Room</span><span class="text-white text-right">{{ $roomName }}</span>
                    <span class="text-slate-400">Dates</span><span class="text-white text-right" id="aw-summary-dates">—</span>
                    <span class="text-slate-400">Guests</span><span class="text-white text-right" id="aw-summary-guests">1</span>
                    <span class="text-slate-400">Floor</span><span class="text-white text-right" id="aw-summary-floor">Floor 1</span>
                    <span class="text-slate-400">Ambiance</span><span class="text-white text-right" id="aw-summary-ambiance">Regular Room</span>
                    <span class="text-slate-400">Food package</span><span class="text-white text-right" id="aw-summary-food">No Food</span>
                    <span class="text-slate-400">Nights</span><span class="text-white text-right" id="aw-summary-nights">—</span>
                </div>
                <div class="mt-3 pt-3 border-t border-white/10 flex justify-between items-center">
                    <span class="text-xs uppercase tracking-widest text-slate-400">Final total</span>
                    <span class="text-xl font-semibold text-amber-400" id="aw-summary-total">₱{{ number_format($price) }}</span>
                </div>
            </div>

            <p id="aw-submit-error" class="hidden mt-3 text-sm text-red-400"></p>

            <div class="mt-5 flex items-center justify-between gap-3">
                <button type="button" onclick="awGoToStep(1)" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-medium text-slate-300 hover:bg-white/5">Back</button>
                <button type="button" id="aw-submit-btn" onclick="awSubmitBooking()" aria-busy="false"
                    class="relative min-w-[9.5rem] justify-center rounded-xl bg-amber-400 px-5 py-2.5 text-sm font-semibold text-slate-900 hover:bg-amber-300 flex items-center gap-2 transition-opacity disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:bg-amber-400">
                    <i class="fas fa-spinner fa-spin hidden" id="aw-submit-spinner"></i>
                    <span id="aw-submit-label">Confirm booking</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    const BASE_PRICE = {{ $price }};
    const disabledDates = @json($disabledDates);

    const groupAddons = { ambiance: 0, food_package: 0 };
    let selectedNights = 0;

    function q(id) { return document.getElementById(id); }

    function recalcTotal() {
        const addonPerNight = (groupAddons.ambiance || 0) + (groupAddons.food_package || 0);
        const effectiveNights = selectedNights > 0 ? selectedNights : 1;
        const total = (BASE_PRICE + addonPerNight) * effectiveNights;

        q('aw-addon-display').textContent = '₱' + addonPerNight.toLocaleString();
        q('aw-nights-display').textContent = selectedNights > 0 ? selectedNights : '—';
        q('aw-input-addons').value = addonPerNight;
        q('aw-input-total').value = total;
        q('aw-input-nights').value = selectedNights;
        q('aw-total-price').textContent = '₱' + total.toLocaleString();
    }

    function awGoToStep(step) {
        document.querySelectorAll('.aw-step').forEach(el => {
            el.classList.toggle('hidden', el.dataset.step !== String(step));
        });
        document.querySelectorAll('.aw-step-pill').forEach(pill => {
            const pillStep = Number(pill.dataset.step);
            const track = pill.querySelector('.aw-pill-track');
            const isActive = pillStep === step;
            const isComplete = pillStep < step;
            track.className = 'h-1.5 rounded-full ' +
                (isActive || isComplete ? 'bg-amber-400' : 'bg-white/10');
        });
        q('aw-step-label-1').className = step === 1 ? 'text-amber-400' : (step > 1 ? 'text-slate-300' : '');
        q('aw-step-label-2').className = step === 2 ? 'text-amber-400' : '';
        if (step === 2) updateSummary();
    }
    window.awGoToStep = awGoToStep;

    function updateSummary() {
        q('aw-summary-dates').textContent = q('aw-booking_range').value || '—';
        q('aw-summary-guests').textContent = q('aw-input-guests').value || '1';
        q('aw-summary-floor').textContent = q('aw-input-floor').value || 'Floor 1';
        q('aw-summary-ambiance').textContent = q('aw-input-ambiance').value || 'Regular Room';
        q('aw-summary-food').textContent = q('aw-input-food').value || 'No Food';
        q('aw-summary-nights').textContent = selectedNights > 0 ? selectedNights : '—';
        q('aw-summary-total').textContent = q('aw-total-price').textContent;
    }

    window.awNextStep = function (currentStep) {
        if (currentStep === 1) {
            const nights = parseInt(q('aw-input-nights').value || 0);
            if (!q('aw-check_in').value || !q('aw-check_out').value || nights < 1) {
                alert('Please select your check-in and check-out dates.');
                return;
            }
            awGoToStep(2);
            return;
        }
    };

    window.awStepGuests = function (delta) {
        const input = q('aw-pax_count');
        const max = parseInt(input.max) || 10;
        let val = Math.min(max, Math.max(1, (parseInt(input.value) || 1) + delta));
        input.value = val;
        q('aw-input-guests').value = val;
    };

    let awIsSubmitting = false;

    window.awSubmitBooking = function () {
        if (awIsSubmitting) return; // guards against double-clicks / double-taps, even if the disabled attribute is bypassed
        awIsSubmitting = true;

        const btn = q('aw-submit-btn');
        const spinner = q('aw-submit-spinner');
        const label = q('aw-submit-label');
        const errorEl = q('aw-submit-error');

        errorEl.classList.add('hidden');
        btn.disabled = true;
        btn.setAttribute('aria-busy', 'true');
        spinner.classList.remove('hidden');
        label.textContent = 'Booking…';

        const formData = new FormData(q('auth-wizard-form'));

        fetch('{{ route('reservations.booking.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) {
                const message = data.message
                    || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Something went wrong. Please try again.');
                throw new Error(message);
            }
            return data;
        })
        .then(data => {
            // keep the button locked/spinning through the redirect so a stray click can't fire a second submit
            window.location.href = data.redirect;
        })
        .catch(err => {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
            awIsSubmitting = false;
            btn.disabled = false;
            btn.setAttribute('aria-busy', 'false');
            spinner.classList.add('hidden');
            label.textContent = 'Confirm booking';
        });
    };

    // option row selection
    document.querySelectorAll('.aw-option-row').forEach(row => {
        row.addEventListener('click', function () {
            const group = this.dataset.group;
            const price = parseInt(this.dataset.price) || 0;
            const label = this.dataset.label;

            document.querySelectorAll(`.aw-option-row[data-group="${group}"]`).forEach(r => {
                r.classList.remove('aw-selected');
                const badge = r.querySelector('.aw-badge');
                const p = parseInt(r.dataset.price) || 0;
                badge.className = 'aw-badge';
                badge.textContent = group === 'floor_level' ? 'Free'
                    : (p === 0 ? (group === 'ambiance' ? 'Base' : 'Free') : '+₱' + p.toLocaleString());
            });

            this.classList.add('aw-selected');
            const badge = this.querySelector('.aw-badge');
            badge.className = 'aw-badge aw-badge-active';
            badge.textContent = group === 'floor_level' ? 'Free'
                : (price === 0 ? (group === 'ambiance' ? 'Base' : 'Free') : '+₱' + price.toLocaleString());

            if (group === 'floor_level') q('aw-input-floor').value = label;
            if (group === 'ambiance') { q('aw-input-ambiance').value = label; groupAddons.ambiance = price; }
            if (group === 'food_package') { q('aw-input-food').value = label; groupAddons.food_package = price; }

            recalcTotal();
        });
    });

    // datepicker
    const datepicker = new HotelDatepicker(q('aw-booking_range'), {
        format: 'MMM D, YYYY',
        minNights: 1,
        disabledDates: disabledDates,
        clearButton: true,
        selectForward: true,
        onSelectRange() {
            const value = q('aw-booking_range').value.trim();
            const parts = value.split(' - ');
            if (parts.length !== 2) return;

            const start = new Date(parts[0]);
            const end = new Date(parts[1]);

            q('aw-check_in').value = fecha.format(start, 'YYYY-MM-DD');
            q('aw-check_out').value = fecha.format(end, 'YYYY-MM-DD');

            const msPerDay = 1000 * 60 * 60 * 24;
            selectedNights = Math.round((end - start) / msPerDay);
            q('aw-input-nights').value = selectedNights;

            q('aw-nights-text').textContent = selectedNights + ' night' + (selectedNights !== 1 ? 's' : '');
            q('aw-nights-label').classList.remove('hidden');

            recalcTotal();
        }
    });

    recalcTotal();
})();
</script>

<style>
    /* Compact chip-style option rows (replaces the old full-width list rows) */
    #auth-wizard-root .aw-chip {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2px;
        text-align: center;
        border-radius: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.5rem 0.375rem;
        cursor: pointer;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }
    #auth-wizard-root .aw-chip:hover {
        background: rgba(255, 255, 255, 0.05);
    }
    #auth-wizard-root .aw-chip-label {
        font-size: 0.72rem;
        line-height: 1.05rem;
        color: #e2e8f0; /* slate-200 */
        font-weight: 500;
    }
    #auth-wizard-root .aw-badge {
        font-size: 0.62rem;
        font-weight: 500;
        color: #94a3b8; /* slate-400 */
    }
    #auth-wizard-root .aw-badge-active {
        color: #fbbf24; /* amber-400 */
    }
    #auth-wizard-root .aw-option-row.aw-selected {
        background: rgba(251, 191, 36, 0.12);
        border-color: rgba(251, 191, 36, 0.5) !important;
    }

    /* Keeps the price summary visible near the bottom of the scroll area on step 1 */
    #auth-wizard-root .aw-price-bar {
        position: sticky;
        bottom: -1px;
    }
</style>

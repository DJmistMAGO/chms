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

        {{-- Step pills --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="aw-step-pill text-center" data-step="1">
                <div class="aw-pill mx-auto flex h-9 w-9 items-center justify-center rounded-full bg-amber-400 text-slate-900 text-sm font-semibold">1</div>
                <span class="mt-1 block text-[10px] uppercase tracking-widest text-slate-400">Options</span>
            </div>
            <div class="aw-step-pill text-center" data-step="2">
                <div class="aw-pill mx-auto flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-slate-400 text-sm font-semibold">2</div>
                <span class="mt-1 block text-[10px] uppercase tracking-widest text-slate-400">Review</span>
            </div>
        </div>

        {{-- STEP 1: OPTIONS --}}
        <div class="aw-step" data-step="1">
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Guests</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="awStepGuests(-1)" class="w-10 h-10 rounded-xl border border-white/10 text-lg font-semibold text-white hover:bg-white/5">−</button>
                        <input type="number" id="aw-pax_count" value="1" min="1" max="{{ $room->capacity }}" class="flex-1 rounded-xl border border-white/10 bg-white/5 py-2.5 text-center text-sm font-semibold text-white">
                        <button type="button" onclick="awStepGuests(1)" class="w-10 h-10 rounded-xl border border-white/10 text-lg font-semibold text-white hover:bg-white/5">+</button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Dates</label>
                    <input type="text" id="aw-booking_range" placeholder="Select check-in → check-out" readonly
                        class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white cursor-pointer">
                    <p id="aw-nights-badge" class="hidden mt-2 text-xs font-medium text-amber-400">
                        <i class="fas fa-moon mr-1"></i><span id="aw-nights-label"></span>
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Floor Level</label>
                    <div class="space-y-2">
                        @foreach (['Floor 1', 'Floor 2', 'Floor 4'] as $i => $floor)
                            <div class="aw-option-row flex items-center justify-between rounded-xl border border-white/10 px-4 py-3 cursor-pointer {{ $i === 0 ? 'aw-selected' : '' }}"
                                data-group="floor_level" data-price="0" data-label="{{ $floor }}">
                                <span class="text-sm text-slate-200">{{ $floor }}</span>
                                <span class="aw-badge text-xs font-medium px-3 py-1 rounded-full bg-white/10 text-slate-400">Free</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Ambiance</label>
                    <div class="space-y-2">
                        @foreach ([['label' => 'Regular Room', 'price' => 0], ['label' => 'Cozy Ambiance', 'price' => 500], ['label' => 'Romantic Ambiance', 'price' => 1000]] as $i => $ambiance)
                            <div class="aw-option-row flex items-center justify-between rounded-xl border border-white/10 px-4 py-3 cursor-pointer {{ $i === 0 ? 'aw-selected' : '' }}"
                                data-group="ambiance" data-price="{{ $ambiance['price'] }}" data-label="{{ $ambiance['label'] }}">
                                <span class="text-sm text-slate-200">{{ $ambiance['label'] }}</span>
                                <span class="aw-badge text-xs font-medium px-3 py-1 rounded-full bg-white/10 text-slate-400">{{ $ambiance['price'] == 0 ? 'Base' : '+ ₱' . number_format($ambiance['price']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Food Package</label>
                    <div class="space-y-2">
                        @foreach ([['label' => 'No Food', 'price' => 0], ['label' => 'Cozy Dinner for Family', 'price' => 1500], ['label' => 'Romantic Dinner', 'price' => 1500]] as $i => $food)
                            <div class="aw-option-row flex items-center justify-between rounded-xl border border-white/10 px-4 py-3 cursor-pointer {{ $i === 0 ? 'aw-selected' : '' }}"
                                data-group="food_package" data-price="{{ $food['price'] }}" data-label="{{ $food['label'] }}">
                                <span class="text-sm text-slate-200">{{ $food['label'] }}</span>
                                <span class="aw-badge text-xs font-medium px-3 py-1 rounded-full bg-white/10 text-slate-400">{{ $food['price'] == 0 ? 'Free' : '+ ₱' . number_format($food['price']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-4 space-y-2">
                <div class="flex justify-between text-sm text-slate-400"><span>Rate / night</span><span class="text-white font-medium">₱{{ number_format($price) }}</span></div>
                <div class="flex justify-between text-sm text-slate-400"><span>Add-ons / night</span><span class="text-white font-medium" id="aw-addon-display">₱0</span></div>
                <div class="flex justify-between text-sm text-slate-400"><span>Nights</span><span class="text-white font-medium" id="aw-nights-display">—</span></div>
                <div class="pt-2 border-t border-white/10 flex justify-between items-center">
                    <span class="text-xs uppercase tracking-widest text-slate-400">Total</span>
                    <span class="text-xl font-semibold text-amber-400" id="aw-total-price">₱{{ number_format($price) }}</span>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-between gap-3">
                <button type="button" onclick="backToRoomPicker()" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-medium text-slate-300 hover:bg-white/5">Back to rooms</button>
                <button type="button" onclick="awNextStep(1)" class="rounded-xl bg-amber-400 px-5 py-2.5 text-sm font-semibold text-slate-900 hover:bg-amber-300">Continue to review</button>
            </div>
        </div>

        {{-- STEP 2: REVIEW --}}
        <div class="aw-step hidden" data-step="2">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 space-y-3">
                <div class="flex justify-between text-sm text-slate-400"><span>Room</span><span class="text-white">{{ $roomName }}</span></div>
                <div class="flex justify-between text-sm text-slate-400"><span>Dates</span><span class="text-white" id="aw-summary-dates">—</span></div>
                <div class="flex justify-between text-sm text-slate-400"><span>Guests</span><span class="text-white" id="aw-summary-guests">1</span></div>
                <div class="flex justify-between text-sm text-slate-400"><span>Floor</span><span class="text-white" id="aw-summary-floor">Floor 1</span></div>
                <div class="flex justify-between text-sm text-slate-400"><span>Ambiance</span><span class="text-white" id="aw-summary-ambiance">Regular Room</span></div>
                <div class="flex justify-between text-sm text-slate-400"><span>Food package</span><span class="text-white" id="aw-summary-food">No Food</span></div>
                <div class="flex justify-between text-sm text-slate-400"><span>Nights</span><span class="text-white" id="aw-summary-nights">—</span></div>
                <div class="pt-3 border-t border-white/10 flex justify-between items-center">
                    <span class="text-xs uppercase tracking-widest text-slate-400">Final total</span>
                    <span class="text-xl font-semibold text-amber-400" id="aw-summary-total">₱{{ number_format($price) }}</span>
                </div>
            </div>

            <p id="aw-submit-error" class="hidden mt-3 text-sm text-red-400"></p>

            <div class="mt-5 flex items-center justify-between gap-3">
                <button type="button" onclick="awGoToStep(1)" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-medium text-slate-300 hover:bg-white/5">Back</button>
                <button type="button" id="aw-submit-btn" onclick="awSubmitBooking()" class="rounded-xl bg-amber-400 px-5 py-2.5 text-sm font-semibold text-slate-900 hover:bg-amber-300 flex items-center gap-2">
                    <span>Confirm booking</span>
                    <i class="fas fa-spinner fa-spin hidden" id="aw-submit-spinner"></i>
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
            const dot = pill.querySelector('.aw-pill');
            const isActive = pillStep === step;
            const isComplete = pillStep < step;
            dot.className = 'aw-pill mx-auto flex h-9 w-9 items-center justify-center rounded-full text-sm font-semibold ' +
                (isActive ? 'bg-amber-400 text-slate-900' : isComplete ? 'bg-amber-400/40 text-slate-900' : 'bg-white/10 text-slate-400');
        });
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

    window.awSubmitBooking = function () {
        const btn = q('aw-submit-btn');
        const spinner = q('aw-submit-spinner');
        const errorEl = q('aw-submit-error');
        errorEl.classList.add('hidden');
        btn.disabled = true;
        spinner.classList.remove('hidden');

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
            window.location.href = data.redirect;
        })
        .catch(err => {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled = false;
            spinner.classList.add('hidden');
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
                badge.className = 'aw-badge text-xs font-medium px-3 py-1 rounded-full bg-white/10 text-slate-400';
                badge.textContent = group === 'floor_level' ? 'Free'
                    : (p === 0 ? (group === 'ambiance' ? 'Base' : 'Free') : '+ ₱' + p.toLocaleString());
            });

            this.classList.add('aw-selected');
            const badge = this.querySelector('.aw-badge');
            badge.className = 'aw-badge text-xs font-medium px-3 py-1 rounded-full bg-amber-400/20 text-amber-400';
            badge.textContent = group === 'floor_level' ? 'Free'
                : (price === 0 ? (group === 'ambiance' ? 'Base' : 'Free') : '+ ₱' + price.toLocaleString());

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

            q('aw-nights-label').textContent = selectedNights + ' night' + (selectedNights !== 1 ? 's' : '');
            q('aw-nights-badge').classList.remove('hidden');

            recalcTotal();
        }
    });

    recalcTotal();
})();
</script>

<style>
    #auth-wizard-root .aw-option-row.aw-selected {
        background: rgba(251, 191, 36, 0.12);
        border-color: rgba(251, 191, 36, 0.5) !important;
    }
</style>

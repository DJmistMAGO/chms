@props(['referenceNumber' => null])

@if ($referenceNumber)
    <div x-data="{ open: true }" x-init="$watch('open', value => document.body.style.overflow = value ? 'hidden' : 'unset')">
        <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4 py-6">
            <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-6 relative" @click.away="open = false">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Booking Confirmed</h2>
                <p class="text-gray-600 mb-4">Your reference number is:</p>
                <div class="text-2xl font-bold text-amber-500 mb-4">{{ $referenceNumber }}</div>
                <p class="text-sm text-gray-500">
                    Please check your email for full booking details. You can also view your booking history and details in your
                    <a href="{{ route('dashboard') }}" class="underline text-indigo-600 hover:text-indigo-800">My Reservations</a>.
                </p>

                <div class="mt-5 text-right">
                    <button type="button" @click="open = false" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">OK</button>
                </div>
            </div>
        </div>
    </div>
@endif

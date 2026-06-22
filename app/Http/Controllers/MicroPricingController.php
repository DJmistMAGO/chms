<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class MicroPricingController extends Controller
{
    public function booking($roomType)
    {
        $rooms = [
            'standard' => [
                'name' => 'Standard Room',
                'price' => 1500,
                'total_rooms' => 14,
                'capacity' => 2,
                'image' => 'assets/images/pRoom.png',
                'size' => 30,
                'bed' => '1 King Bed',
                'amenities' => [
                    'Air Conditioning',
                    'Cable TV',
                    'Toilet',
                    'Bathtub',
                    'High-Speed WiFi',
                    'Private Bathroom',
                    'Work Desk',
                ],
            ],
            'standard-premium' => [
                'name' => 'Standard Premium Room',
                'price' => 1900,
                'total_rooms' => 10,
                'capacity' => 2,
                'image' => 'assets/images/pRoom.png',
                'size' => 35,
                'bed' => '1 King Bed',
                'amenities' => [
                    'Air Conditioning',
                    'Cable TV',
                    'Toilet',
                    'Bathtub',
                    'High-Speed WiFi',
                    'Private Bathroom',
                    'Work Desk',
                ],
            ],
            'family' => [
                'name' => 'Family Room',
                'price' => 2700,
                'total_rooms' => 4,
                'capacity' => 6,
                'image' => 'assets/images/pRoom.png',
                'size' => 50,
                'bed' => '2 Queen Beds',
                'amenities' => [
                    'Air Conditioning',
                    'Cable TV',
                    'Toilet',
                    'Bathtub',
                    'High-Speed WiFi',
                    'Private Bathroom',
                    'Dining Table',
                ],
            ],
        ];

        if (! isset($rooms[$roomType])) {
            abort(404);
        }

        $room = (object) $rooms[$roomType];
        $roomName = $room->name;
        $price = $room->price;
        $totalRooms = $room->total_rooms;

        $bookings = Booking::where('room_type', $roomName)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $dateCounts = [];

        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->check_in)->startOfDay();
            $end = Carbon::parse($booking->check_out)->startOfDay()->subDay();

            if ($end->lt($start)) {
                continue;
            }

            $period = CarbonPeriod::create($start, $end);

            foreach ($period as $date) {
                $formatted = $date->format('Y-m-d');
                $dateCounts[$formatted] = ($dateCounts[$formatted] ?? 0) + 1;
            }
        }

        $disabledDates = [];
        foreach ($dateCounts as $date => $count) {
            if ($count >= $totalRooms) {
                $disabledDates[] = $date;
            }
        }

        return view('micro-pricing', compact(
            'room',
            'roomName',
            'price',
            'disabledDates'
        ));
    }

    public function loginOrRegisterWithBooking(Request $request)
    {
        $validated = $this->validateBookingRequest($request, true);

        DB::beginTransaction();

        try {
            $user = User::where('email', $validated['email'])->first();

            if ($user) {
                if (! Auth::attempt([
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ], $request->boolean('remember'))) {
                    throw ValidationException::withMessages([
                        'email' => ['Invalid email or password.'],
                    ]);
                }

                $user = Auth::user();
            } else {
                $user = User::create([
                    'name' => $this->buildAutoName($validated['email']),
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);

                Auth::login($user, $request->boolean('remember'));
            }

            $booking = $this->createBookingFromValidated($validated, $request, $user->id);

            DB::commit();

            return redirect()
                ->route('dashboard') // change to your actual route
                ->with('success', 'Booking submitted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($e instanceof ValidationException) {
                throw $e;
            }

            return back()
                ->withInput()
                ->withErrors(['general' => 'Unable to complete booking. Please try again.']);
        }
    }

    public function storeGoogleBookingSession(Request $request)
    {
        $validated = $this->validateBookingRequest($request, false);

        if ($request->hasFile('valid_id_path')) {
            $tempPath = $request->file('valid_id_path')->store('booking-ids/temp', 'public');
            $validated['valid_id_temp_path'] = $tempPath;
        }

        session([
            'pending_google_booking' => $validated,
        ]);

        return redirect()->route('booking.google.redirect');
    }

    protected function validateBookingRequest(Request $request, bool $withCredentials = true): array
    {
        $rules = [
            'room_type' => ['required', 'string', 'max:255'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'number_of_guests' => ['required', 'integer', 'min:1'],
            'nights' => ['required', 'integer', 'min:1'],
            'floor_level' => ['required', Rule::in(['Floor 1', 'Floor 2', 'Floor 4'])],
            'ambiance' => ['required', Rule::in(['Regular Room', 'Cozy Ambiance', 'Romantic Ambiance'])],
            'food_package' => ['required', Rule::in(['No Food', 'Cozy Dinner for Family', 'Romantic Dinner'])],
            'room_price' => ['required', 'numeric', 'min:0'],
            'micro_pricing_amount' => ['required', 'numeric', 'min:0'],
            'total_price' => ['required', 'numeric', 'min:0'],
            'valid_id_path' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];

        if ($withCredentials) {
            $rules['email'] = ['required', 'email:rfc,dns'];
            $rules['password'] = ['required', 'string', 'min:8'];
        }

        $validated = $request->validate($rules);

        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $computedNights = $checkIn->diffInDays($checkOut);

        if ($computedNights !== (int) $validated['nights']) {
            throw ValidationException::withMessages([
                'check_in' => ['Night count does not match selected dates.'],
            ]);
        }

        $expectedAddon = $this->getAddonAmount(
            $validated['ambiance'],
            $validated['food_package']
        );

        if ((float) $validated['micro_pricing_amount'] !== (float) $expectedAddon) {
            throw ValidationException::withMessages([
                'micro_pricing_amount' => ['Addon total mismatch.'],
            ]);
        }

        $expectedTotal = ((float) $validated['room_price'] + (float) $validated['micro_pricing_amount']) * $computedNights;

        if ((float) $validated['total_price'] !== (float) $expectedTotal) {
            throw ValidationException::withMessages([
                'total_price' => ['Total price mismatch.'],
            ]);
        }

        return $validated;
    }

    protected function createBookingFromValidated(array $validated, Request $request, int $userId): Booking
    {
        $validIdPath = null;

        if ($request->hasFile('valid_id_path')) {
            $validIdPath = $request->file('valid_id_path')->store('booking-ids', 'public');
        } elseif (!empty($validated['valid_id_temp_path'])) {
            $from = $validated['valid_id_temp_path'];
            $filename = basename($from);
            $to = 'booking-ids/' . $filename;

            if (Storage::disk('public')->exists($from)) {
                Storage::disk('public')->move($from, $to);
                $validIdPath = $to;
            }
        }

        return Booking::create([
            'user_id' => $userId,
            'room_type' => $validated['room_type'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'number_of_guests' => $validated['number_of_guests'],
            'nights' => $validated['nights'],
            'floor_level' => $validated['floor_level'],
            'ambiance' => $validated['ambiance'],
            'food_package' => $validated['food_package'],
            'room_price' => $validated['room_price'],
            'micro_pricing_amount' => $validated['micro_pricing_amount'],
            'total_price' => $validated['total_price'],
            'valid_id_path' => $validIdPath,
            'status' => 'pending',
        ]);
    }

    protected function getAddonAmount(string $ambiance, string $foodPackage): int
    {
        $ambiancePrices = [
            'Regular Room' => 0,
            'Cozy Ambiance' => 500,
            'Romantic Ambiance' => 1000,
        ];

        $foodPrices = [
            'No Food' => 0,
            'Cozy Dinner for Family' => 1500,
            'Romantic Dinner' => 1500,
        ];

        return ($ambiancePrices[$ambiance] ?? 0) + ($foodPrices[$foodPackage] ?? 0);
    }

    protected function buildAutoName(string $email): string
    {
        $name = explode('@', $email)[0] ?? 'Guest';
        $name = str_replace(['.', '_', '-'], ' ', $name);
        $name = Str::title(trim($name));

        return $name ?: 'Guest User';
    }
}

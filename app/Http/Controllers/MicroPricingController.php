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
use App\Traits\HandlesBookingCreation;

class MicroPricingController extends Controller
{
    use HandlesBookingCreation;

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
        $request->validate(array_merge($this->bookingFieldRules(), [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]));

        $validated = $this->validateBookingFields(
            $request->only($this->bookingDataKeys())
        );

        DB::beginTransaction();

        try {
            $user = User::where('email', $request->email)->first();

            if ($user) {
                if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
                    throw ValidationException::withMessages([
                        'email' => ['Invalid email or password.'],
                    ]);
                }
                $user = Auth::user();
            } else {
                $user = User::create([
                    'name'           => $this->buildAutoName($request->email),
                    'email'          => $request->email,
                    'password'       => Hash::make($request->password),
                    'is_google_user' => false,
                ]);
                $user->assignRole('client');
                Auth::login($user, $request->boolean('remember'));
            }

            $booking = $this->persistBooking($validated, $user->id, $request->file('valid_id_path'), $user);

            // if ($booking->valid_id_path) {
            //     $user->update(['valid_id' => $booking->valid_id_path]);
            // }

            DB::commit();

            return redirect()
                ->route('dashboard', ['referenceNumber' => $booking->reference_number])
                ->with('success', 'Booking submitted! We will verify your ID and confirm shortly.');

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;

        } catch (\Throwable $e) {
            DB::rollBack();

            // 👇 Pinpoint exactly where and what failed
            return back()->withInput()->withErrors([
                'general' => '[' . class_basename($e) . '] '
                    . $e->getMessage()
                    . ' — in ' . $e->getFile()
                    . ' on line ' . $e->getLine(),
            ]);
        }
    }

    public function storeGoogleBookingSession(Request $request)
    {
        \Log::info('storeGoogleBookingSession', [
            'hasFile'  => $request->hasFile('valid_id_path'),
            'allFiles' => $request->allFiles(),
            'all'      => $request->except('valid_id_path'),
        ]);

        $request->validate($this->bookingFieldRules());

        $validated = $this->validateBookingFields(
            $request->only($this->bookingDataKeys())
        );

        if ($request->hasFile('valid_id_path')) {
            $validated['valid_id_temp_path'] = $request->file('valid_id_path')
                ->store('booking-ids/temp', 'public');
        }

        session(['pending_google_booking' => $validated]);

        return redirect()->route('booking.google.redirect');
    }

    // protected function createBookingFromValidated(array $validated, Request $request, int $userId): Booking
    // {
    //     $validIdPath = null;

    //     if ($request->hasFile('valid_id_path')) {
    //         $validIdPath = $request->file('valid_id_path')->store('booking-ids', 'public');
    //     } elseif (!empty($validated['valid_id_temp_path'])) {
    //         $from = $validated['valid_id_temp_path'];
    //         $filename = basename($from);
    //         $to = 'booking-ids/' . $filename;

    //         if (Storage::disk('public')->exists($from)) {
    //             Storage::disk('public')->move($from, $to);
    //             $validIdPath = $to;
    //         }
    //     }

    //     return Booking::create([
    //         'user_id' => $userId,
    //         'room_type' => $validated['room_type'],
    //         'check_in' => $validated['check_in'],
    //         'check_out' => $validated['check_out'],
    //         'number_of_guests' => $validated['number_of_guests'],
    //         'nights' => $validated['nights'],
    //         'floor_level' => $validated['floor_level'],
    //         'ambiance' => $validated['ambiance'],
    //         'food_package' => $validated['food_package'],
    //         'room_price' => $validated['room_price'],
    //         'micro_pricing_amount' => $validated['micro_pricing_amount'],
    //         'total_price' => $validated['total_price'],
    //         'valid_id_path' => $validIdPath,
    //         'status' => 'pending',
    //     ]);
    // }

    // protected function getAddonAmount(string $ambiance, string $foodPackage): int
    // {
    //     $ambiancePrices = [
    //         'Regular Room' => 0,
    //         'Cozy Ambiance' => 500,
    //         'Romantic Ambiance' => 1000,
    //     ];

    //     $foodPrices = [
    //         'No Food' => 0,
    //         'Cozy Dinner for Family' => 1500,
    //         'Romantic Dinner' => 1500,
    //     ];

    //     return ($ambiancePrices[$ambiance] ?? 0) + ($foodPrices[$foodPackage] ?? 0);
    // }

    protected function buildAutoName(string $email): string
    {
        $name = explode('@', $email)[0] ?? 'Guest';
        $name = str_replace(['.', '_', '-'], ' ', $name);
        $name = Str::title(trim($name));

        return $name ?: 'Guest User';
    }
}


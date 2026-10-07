<?php

namespace App\Http\Controllers;

use App\Mail\StatusEmail;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\WalkInBooking;
use App\Traits\HandlesBookingCreation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MicroPricingController extends Controller
{
    use HandlesBookingCreation;

    protected function roomCatalog(): array
    {
        return [
            'standard' => [
                'name' => 'Standard Room',
                'price' => 1500,
                'total_rooms' => 14,
                'capacity' => 2,
                'image' => 'assets/images/sRoom.png',
                'size' => 30,
                'bed' => '1 King Bed',
                'amenities' => ['Air Conditioning', 'Cable TV', 'Toilet', 'Bathtub', 'High-Speed WiFi', 'Private Bathroom', 'Work Desk'],
            ],
            'standard-premium' => [
                'name' => 'Standard Premium Room',
                'price' => 1900,
                'total_rooms' => 10,
                'capacity' => 2,
                'image' => 'assets/images/pRoom.png',
                'size' => 35,
                'bed' => '1 King Bed',
                'amenities' => ['Air Conditioning', 'Cable TV', 'Toilet', 'Bathtub', 'High-Speed WiFi', 'Private Bathroom', 'Work Desk'],
            ],
            'family' => [
                'name' => 'Family Room',
                'price' => 2700,
                'total_rooms' => 4,
                'capacity' => 6,
                'image' => 'assets/images/fRoom.png',
                'size' => 50,
                'bed' => '2 Queen Beds',
                'amenities' => ['Air Conditioning', 'Cable TV', 'Toilet', 'Bathtub', 'High-Speed WiFi', 'Private Bathroom', 'Dining Table'],
            ],
        ];
    }

    protected function getRoomCapacityBySlug(string $slug): int
    {
        $catalog = $this->roomCatalog();
        return $catalog[$slug]['total_rooms'] ?? 0;
    }

    protected function ambiancePrices(): array
    {
        return [
            'Regular Room' => 0,
            'Cozy Ambiance' => 500,
            'Romantic Ambiance' => 1000,
        ];
    }

    protected function foodPrices(): array
    {
        return [
            'No Food' => 0,
            'Cozy Dinner for Family' => 1500,
            'Romantic Dinner' => 1700,
        ];
    }

    public function booking($roomType)
    {
        $rooms = $this->roomCatalog();

        if (!isset($rooms[$roomType])) {
            abort(404);
        }

        $room = (object) $rooms[$roomType];
        $roomName = $room->name;
        $price = $room->price;
        $disabledDates = $this->disabledRoomDates($roomName);

        return view('micro-pricing', compact('room', 'roomName', 'roomType', 'price', 'disabledDates'));
    }

    public function checkExistingAccount(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'use_existing_account' => ['required', 'boolean'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if ($request->boolean('use_existing_account')) {
            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['We could not find an account with those credentials.'],
                ]);
            }

            return response()->json([
                'mode' => 'existing',
                'requires_id_upload' => empty($user->valid_id),
                'has_valid_id' => !empty($user->valid_id),
            ]);
        }

        if ($user) {
            throw ValidationException::withMessages([
                'email' => ['An account with this email already exists. Please sign in instead.'],
            ]);
        }

        return response()->json([
            'mode' => 'new',
            'requires_id_upload' => true,
        ]);
    }

    public function loginOrRegisterWithBooking(Request $request)
    {
        $request->validate(
            array_merge($this->bookingFieldRules(), [
                'room_type_slug' => ['required', 'string'],
                'email' => ['required', 'email'],
                'password' => ['required', 'string', 'min:8'],
                'use_existing_account' => ['required', 'boolean'],
            ]),
        );

        $validated = $this->validateBookingFields($request->only($this->bookingDataKeys()));

        $validated = $this->repriceBooking($validated, $request->input('room_type_slug'));

        $useExistingAccount = $request->boolean('use_existing_account');
        $existingUser = User::where('email', $request->email)->first();

        if ($useExistingAccount) {
            if (!$existingUser || !Hash::check($request->password, $existingUser->password)) {
                throw ValidationException::withMessages([
                    'email' => ['We could not find an account with those credentials.'],
                ]);
            }
        } elseif ($existingUser) {
            throw ValidationException::withMessages([
                'email' => ['An account with this email already exists. Please sign in instead.'],
            ]);
        }

        DB::beginTransaction();

        try {
            $this->ensureRoomAvailability($validated, $request->input('room_type_slug'));

            if ($useExistingAccount) {
                $user = $existingUser;

                if ($request->filled('name')) {
                    $user->forceFill(['name' => $request->name])->save();
                }

                Auth::login($user, $request->boolean('remember'));
            } else {
                $user = User::create([
                    'name' => $request->filled('name') ? $request->name : $this->buildAutoName($request->email),
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'is_google_user' => false,
                ]);
                $user->assignRole('client');
                Auth::login($user, $request->boolean('remember'));
            }

            if (empty($user->valid_id) && !$request->hasFile('valid_id_path')) {
                throw ValidationException::withMessages([
                    'valid_id_path' => ['Please upload a valid ID to continue.'],
                ]);
            }

            $booking = $this->persistBooking($validated, $user->id, $request->file('valid_id_path'), $user);

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->withErrors(['general' => 'Something went wrong while processing your booking. Please try again.']);
        }

        try {
            Mail::to($booking->user->email)->send(new StatusEmail($booking)); // Attempt to send the email, but don't fail the booking if it fails
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('dashboard', ['referenceNumber' => $booking->reference_number])
            ->with('success', 'Booking submitted! We will verify your ID and confirm shortly.');
    }

    public function storeGoogleBookingSession(Request $request)
    {
        $request->validate(
            array_merge($this->bookingFieldRules(), [
                'room_type_slug' => ['required', 'string'],
            ]),
        );

        $validated = $this->validateBookingFields($request->only($this->bookingDataKeys()));

        $validated = $this->repriceBooking($validated, $request->input('room_type_slug'));

        if ($request->hasFile('valid_id_path')) {
            $validated['valid_id_temp_path'] = $request->file('valid_id_path')->store('valid-ids/temp', 'public');
        }

        session(['pending_google_booking' => $validated]);

        return redirect()->route('booking.google.redirect');
    }
    protected function repriceBooking(array $validated, ?string $roomTypeSlug): array
    {
        $room = $this->roomCatalog()[$roomTypeSlug] ?? null;

        if (!$room) {
            throw ValidationException::withMessages([
                'room_type' => ['Selected room is no longer available.'],
            ]);
        }

        $ambiance = $validated['ambiance'] ?? 'Regular Room';
        $food = $validated['food_package'] ?? 'No Food';

        $addonPerNight = ($this->ambiancePrices()[$ambiance] ?? 0) + ($this->foodPrices()[$food] ?? 0);
        $nights = max(1, (int) ($validated['nights'] ?? 1));

        $validated['room_type'] = $room['name'];
        $validated['room_price'] = $room['price'];
        $validated['micro_pricing_amount'] = $addonPerNight;
        $validated['total_price'] = ($room['price'] + $addonPerNight) * $nights;

        return $validated;
    }

    protected function buildAutoName(string $email): string
    {
        $name = explode('@', $email)[0] ?? 'Guest';
        $name = str_replace(['.', '_', '-'], ' ', $name);
        $name = Str::title(trim($name));

        return $name ?: 'Guest User';
    }

    public function newBookingRoomOptions()
    {
        $rooms = collect($this->roomCatalog())
            ->map(function ($room, $slug) {
                return [
                    'slug' => $slug,
                    'name' => $room['name'],
                    'price' => $room['price'],
                    'capacity' => $room['capacity'],
                    'image' => asset($room['image']),
                ];
            })
            ->values();

        return response()->json(['rooms' => $rooms]);
    }

    public function newBookingWizard($roomType)
    {
        abort_unless(Auth::check(), 403);

        $rooms = $this->roomCatalog();

        if (!isset($rooms[$roomType])) {
            abort(404);
        }

        $room = (object) $rooms[$roomType];
        $roomName = $room->name;
        $price = $room->price;
        $disabledDates = $this->disabledRoomDates($roomName);

        $user = Auth::user();
        $hasValidId = !empty($user->valid_id);

        return view('components.common.booking-wizard-authenticated', compact('room', 'roomName', 'roomType', 'price', 'disabledDates', 'hasValidId'));
    }

    public function storeAuthenticatedBooking(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Your session has expired. Please log in again.',
                ],
                401,
            );
        }

        $catalog = $this->roomCatalog();
        $request->validate([
            'room_type_slug' => ['required', 'string', \Illuminate\Validation\Rule::in(array_keys($catalog))],
        ]);
        $room = $catalog[$request->input('room_type_slug')];

        $request->validate(
            array_merge($this->bookingFieldRules(), [
                'room_type_slug' => ['required', 'string'],
                'number_of_guests' => ['required', 'integer', 'min:1', 'max:' . $room['capacity']],
                'floor_level' => ['required', 'string', 'in:Floor 1,Floor 2,Floor 4'],
                'ambiance' => ['required', 'string', \Illuminate\Validation\Rule::in(array_keys($this->ambiancePrices()))],
                'food_package' => ['required', 'string', \Illuminate\Validation\Rule::in(array_keys($this->foodPrices()))],
            ]),
        );

        $validated = $this->validateBookingFields($request->only($this->bookingDataKeys()));

        $validated['nights'] = Carbon::parse($validated['check_in'])
            ->startOfDay()
            ->diffInDays(Carbon::parse($validated['check_out'])->startOfDay());

        $validated = $this->repriceBooking($validated, $request->input('room_type_slug'));

        DB::beginTransaction();

        try {
            $this->ensureRoomAvailability($validated, $request->input('room_type_slug'));

            $booking = $this->persistBooking($validated, $user->id, $request->file('valid_id_path'), $user);

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Something went wrong while processing your booking. Please try again.',
                ],
                500,
            );
        }

        $emailSent = false;

        try {
            Mail::to($booking->user->email)->send(new StatusEmail($booking));
            $emailSent = true;
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking submitted! We will verify your ID and confirm shortly.',
            'redirect' => route('dashboard', ['referenceNumber' => $booking->reference_number]),
            'email_sent' => $emailSent,
        ]);
    }

    public function newAuthenticatedBookingWizard(Request $request, $roomType)
    {
        abort_unless(auth()->check(), 403);

        $user = auth()->user();

        $catalog = $this->roomCatalog();

        if (!isset($catalog[$roomType])) {
            abort(404);
        }

        $room = (object) $catalog[$roomType];

        $roomName = $room->name;
        $price = $room->price;

        $disabledDates = $this->disabledRoomDates($roomName);

        return view('booking.new-wizard', compact('user', 'room', 'roomType', 'roomName', 'price', 'disabledDates'));
    }

    public function storeNewBooking(Request $request)
    {
        $user = auth()->user();

        abort_unless($user, 403);

        $request->validate(
            array_merge($this->bookingFieldRules(), [
                'room_type_slug' => ['required', 'string'],
            ]),
        );

        $validated = $this->validateBookingFields($request->only($this->bookingDataKeys()));

        $validated = $this->repriceBooking($validated, $request->input('room_type_slug'));

        DB::beginTransaction();

        try {
            $this->ensureRoomAvailability($validated, $request->input('room_type_slug'));

            $booking = $this->persistBooking($validated, $user->id, $request->file('valid_id_path'), $user);

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'general' => 'Something went wrong while processing your booking. Please try again.',
                ]);
        }

        try {
            Mail::to($booking->user->email)->send(new StatusEmail($booking));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('dashboard', [
                'referenceNumber' => $booking->reference_number,
            ])
            ->with('success', 'Booking submitted! We will verify your ID and confirm shortly.');
    }

    protected function roomInventoryCount(string $roomName, ?string $floorLevel = null, bool $lock = false): int
    {
        // 1. First check total rooms defined in room catalog for this room name
        $catalogRoom = collect($this->roomCatalog())->firstWhere('name', $roomName);
        $totalCatalogCapacity = $catalogRoom['total_rooms'] ?? 0;

        // 2. Query database for physical room inventory
        $query = Room::where('room_type', $roomName)->whereNotIn('status', ['Maintenance', 'Out of Order', 'Reserved']);

        if ($floorLevel) {
            $query->where('floor', $floorLevel);
        }

        if ($lock) {
            $dbCount = $query
                ->lockForUpdate()
                ->get(['id'])
                ->count();
        } else {
            $dbCount = $query->count();
        }

        // Return DB count if room rows exist, otherwise fall back to catalog total_rooms
        return $dbCount > 0 ? $dbCount : ($totalCapacityCatalogFallback = $totalCatalogCapacity);
    }

    protected function roomOccupancyByDate(string $roomName, ?Carbon $rangeStart = null, ?Carbon $rangeEnd = null, ?string $floorLevel = null): array
{
    $rangeStart = $rangeStart ? $rangeStart->copy()->startOfDay() : Carbon::today();
    $rangeEnd = $rangeEnd ? $rangeEnd->copy()->startOfDay() : null;

    $dateCounts = [];

    $addStay = function ($checkIn, $checkOut) use (&$dateCounts, $rangeStart, $rangeEnd) {
        $start = Carbon::parse($checkIn)->startOfDay();
        $end = Carbon::parse($checkOut)->startOfDay()->subDay();

        if ($start->lt($rangeStart)) {
            $start = $rangeStart->copy();
        }

        if ($rangeEnd && $end->gte($rangeEnd)) {
            $end = $rangeEnd->copy()->subDay();
        }

        if ($end->lt($start)) {
            return;
        }

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $formatted = $date->format('Y-m-d');
            $dateCounts[$formatted] = ($dateCounts[$formatted] ?? 0) + 1;
        }
    };

    $bookings = Booking::where('room_type', $roomName)
        ->whereIn(DB::raw('LOWER(status)'), [
            'pending',
            'confirmed',
            'checked in',
            'checked-in'
        ])
        ->whereHas('room', function ($query) use ($floorLevel, $roomName) {
            $query->where('room_type', $roomName);

            if ($floorLevel) {
                $query->where('floor', $floorLevel);
            }
        })
        ->whereDate('check_in', '<', $rangeEnd ? $rangeEnd->format('Y-m-d') : '9999-12-31')
        ->whereDate('check_out', '>', $rangeStart->format('Y-m-d'))
        ->get(['check_in', 'check_out']);

    foreach ($bookings as $booking) {
        $addStay($booking->check_in, $booking->check_out);
    }

    $walkIns = WalkInBooking::whereIn(DB::raw('LOWER(status)'), [
        'confirmed',
        'checked in',
        'checked-in'
    ])
        ->whereHas('room', function ($query) use ($roomName, $floorLevel) {
            $query->where('room_type', $roomName);

            if ($floorLevel) {
                $query->where('floor', $floorLevel);
            }
        })
        ->whereDate('check_in', '<', $rangeEnd ? $rangeEnd->format('Y-m-d') : '9999-12-31')
        ->whereDate('check_out', '>', $rangeStart->format('Y-m-d'))
        ->get(['check_in', 'check_out']);

    foreach ($walkIns as $walkIn) {
        $addStay($walkIn->check_in, $walkIn->check_out);
    }

    return $dateCounts;
}

    protected function disabledRoomDates(string $roomName, ?string $floorLevel = null): array
    {
        $roomCapacity = $this->roomInventoryCount($roomName, $floorLevel);
        if ($roomCapacity === 0) {
            return [];
        }

        // Look ahead up to 1 year from today for disabled calendar dates
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addYear();

        $disabledDates = [];
        $occupancy = $this->roomOccupancyByDate($roomName, $startDate, $endDate, $floorLevel);

        foreach ($occupancy as $date => $occupiedCount) {
            if ($occupiedCount >= $roomCapacity) {
                $disabledDates[] = $date;
            }
        }

        return array_values(array_unique($disabledDates));
    }

    protected function ensureRoomAvailability(array $validated, string $roomTypeSlug): void
    {
        $room = $this->roomCatalog()[$roomTypeSlug] ?? null;
        if (!$room) {
            throw ValidationException::withMessages([
                'room_type' => ['Selected room type is no longer available.'],
            ]);
        }

        $checkIn = Carbon::parse($validated['check_in'])->startOfDay();
        $checkOut = Carbon::parse($validated['check_out'])->startOfDay();
        $floorLevel = $validated['floor_level'] ?? null;

        $roomCapacity = $this->roomInventoryCount($room['name'], $floorLevel, true);
        $dateCounts = $this->roomOccupancyByDate($room['name'], $checkIn, $checkOut, $floorLevel);

        foreach (CarbonPeriod::create($checkIn, $checkOut->copy()->subDay()) as $date) {
            $formattedDate = $date->format('Y-m-d');
            $occupied = $dateCounts[$formattedDate] ?? 0;

            if ($roomCapacity === 0 || $occupied >= $roomCapacity) {
                throw ValidationException::withMessages([
                    'check_in' => ["The {$room['name']} is fully booked for date {$formattedDate}" . ($floorLevel ? " on {$floorLevel}." : '.')],
                ]);
            }
        }
    }

    protected function getDisabledFloors(string $roomType, string $checkIn, string $checkOut): array
    {
        $floors = ['Floor 1', 'Floor 2', 'Floor 4'];
        $disabledFloors = [];

        foreach ($floors as $floor) {
            $floorNum = (int) filter_var($floor, FILTER_SANITIZE_NUMBER_INT);
            $capacity = $this->roomInventoryCount($roomType, $floor);

            // Standard check using existing occupancy logic
            $occupancy = $this->roomOccupancyByDate($roomType, Carbon::parse($checkIn), Carbon::parse($checkOut), $floor);

            // If any date in the stay is at capacity for this floor, disable the floor
            foreach ($occupancy as $date => $count) {
                if ($capacity === 0 || $count >= $capacity) {
                    $disabledFloors[] = $floor;
                    break;
                }
            }
        }

        return $disabledFloors;
    }

    public function checkFloorAvailability(Request $request,$roomType)
{
    $checkIn =$request->query('check_in');
    $checkOut =$request->query('check_out');

    // Fetch all active bookings overlapping with the selected dates
    $bookings = Booking::where('status', '!=', 'cancelled')
        ->where(function ($query) use ($checkIn,$checkOut) {
            $query->where('check_in', '<',$checkOut)
                  ->where('check_out', '>', $checkIn);
        })
        ->get();

    return response()->json([
        'status'   => 'success',
        'check_in' => $checkIn,
        'check_out'=> $checkOut,
        'bookings' => $bookings
    ]);
}

//    public function checkFloorAvailability(Request $request, $roomType)
// {
//     $checkIn = $request->query('check_in');
//     $checkOut = $request->query('check_out');

//     $floors = ['Floor 1', 'Floor 2', 'Floor 4'];
//     $availability = [];

//     foreach ($floors as $floor) {
//         // 1. Get total active rooms on this floor for this room type
//         $totalRooms = Room::where('room_type', $roomType)
//             ->where('floor', $floor)
//             ->whereNotIn('status', ['Maintenance', 'Out of Order'])
//             ->count();

//         // 2. Count occupied rooms during the requested dates
//         // 2. Count occupied rooms during the requested dates
//         $bookedRooms = Room::where('room_type', $roomType)
//             ->where('floor_level', $floor)
//             ->whereHas('bookings', function ($q) use ($checkIn, $checkOut) {
//                 // FIXED: Changed $q::where to $q->where
//                 $q->where('status', '!=', 'Cancelled')
//                 ->where('check_in', '<', $checkOut)
//                 ->where('check_out', '>', $checkIn);
//             })
//             ->count();


//         // 3. Mark as fully booked if total rooms > 0 and available rooms <= 0
//         $isFullyBooked = ($totalRooms > 0) && ($bookedRooms >= $totalRooms);

//         $availability[$floor] = [
//             'capacity' => $totalRooms,
//             'fully_booked' => $isFullyBooked,
//         ];
//     }

//     return response()->json([
//         'floors' => $availability
//     ]);
// }

}

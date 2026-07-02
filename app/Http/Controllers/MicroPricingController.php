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
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Traits\HandlesBookingCreation;

class MicroPricingController extends Controller
{
    use HandlesBookingCreation;

    /**
     * Single source of truth for room data + pricing. Both the booking
     * page and the price-recalculation step read from here, keyed by slug.
     * NEVER derive price from anything the client submits.
     */
    protected function roomCatalog(): array
    {
        return [
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
    }

    /** Canonical add-on pricing — also the single source of truth for repricing. */
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
            'Romantic Dinner' => 1500,
        ];
    }

    public function booking($roomType)
    {
        $rooms = $this->roomCatalog();

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
            'roomType',
            'price',
            'disabledDates'
        ));
    }

    /**
     * Called from Step 2 before ID upload / review. One unambiguous
     * outcome per mode — either it succeeds with account info, or it
     * throws a ValidationException. No silent "mode: new" fallback when
     * the user explicitly asked to sign in.
     */
    public function checkExistingAccount(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'use_existing_account' => ['required', 'boolean'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if ($request->boolean('use_existing_account')) {
            // Sign-in path: generic error either way, no email enumeration.
            if (! $user || ! Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['We could not find an account with those credentials.'],
                ]);
            }

            return response()->json([
                'mode' => 'existing',
                'requires_id_upload' => empty($user->valid_id),
                'has_valid_id' => ! empty($user->valid_id),
            ]);
        }

        // Registration path: block collisions here, before the user
        // fills out ID upload + review only to hit an error at the end.
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
        $request->validate(array_merge($this->bookingFieldRules(), [
            'room_type_slug' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'use_existing_account' => ['required', 'boolean'],
        ]));

        $validated = $this->validateBookingFields(
            $request->only($this->bookingDataKeys())
        );

        // Server is the only source of truth for money. Whatever the
        // client sent for room_price / micro_pricing_amount / total_price
        // is discarded and recomputed here.
        $validated = $this->repriceBooking($validated, $request->input('room_type_slug'));

        $useExistingAccount = $request->boolean('use_existing_account');
        $existingUser = User::where('email', $request->email)->first();

        if ($useExistingAccount) {
            if (! $existingUser || ! Hash::check($request->password, $existingUser->password)) {
                throw ValidationException::withMessages([
                    'email' => ['We could not find an account with those credentials.'],
                ]);
            }
        } elseif ($existingUser) {
            // Do NOT silently Auth::attempt() here — that's how a "new
            // guest" form submission can end up logged into someone
            // else's account. Force them back through the sign-in path.
            throw ValidationException::withMessages([
                'email' => ['An account with this email already exists. Please sign in instead.'],
            ]);
        }

        DB::beginTransaction();

        try {
            if ($useExistingAccount) {
                $user = $existingUser;

                if ($request->filled('name')) {
                    $user->forceFill(['name' => $request->name])->save();
                }

                Auth::login($user, $request->boolean('remember'));
            } else {
                $user = User::create([
                    'name'           => $request->filled('name') ? $request->name : $this->buildAutoName($request->email),
                    'email'          => $request->email,
                    'password'       => Hash::make($request->password),
                    'is_google_user' => false,
                ]);
                $user->assignRole('client');
                Auth::login($user, $request->boolean('remember'));
            }

            if (empty($user->valid_id) && ! $request->hasFile('valid_id_path')) {
                throw ValidationException::withMessages([
                    'valid_id_path' => ['Please upload a valid ID to continue.'],
                ]);
            }

            $booking = $this->persistBooking($validated, $user->id, $request->file('valid_id_path'), $user);

            DB::commit();

            return redirect()
                ->route('dashboard', ['referenceNumber' => $booking->reference_number])
                ->with('success', 'Booking submitted! We will verify your ID and confirm shortly.');

        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Booking submission failed', [
                'exception' => $e,
                'email' => $request->email,
            ]);

            return back()->withInput()->withErrors([
                'general' => 'Something went wrong while processing your booking. Please try again, or contact us if the problem continues.',
            ]);
        }
    }

    public function storeGoogleBookingSession(Request $request)
    {
        $request->validate(array_merge($this->bookingFieldRules(), [
            'room_type_slug' => ['required', 'string'],
        ]));

        $validated = $this->validateBookingFields(
            $request->only($this->bookingDataKeys())
        );

        $validated = $this->repriceBooking($validated, $request->input('room_type_slug'));

        if ($request->hasFile('valid_id_path')) {
            $validated['valid_id_temp_path'] = $request->file('valid_id_path')
                ->store('booking-ids/temp', 'public');
        }

        session(['pending_google_booking' => $validated]);

        return redirect()->route('booking.google.redirect');
    }

    /**
     * Recompute room_price / micro_pricing_amount / total_price from the
     * canonical catalog + add-on tables. Anything the client submitted
     * for these three fields is ignored — this is the fix for the
     * client-controlled-pricing issue.
     */
    protected function repriceBooking(array $validated, ?string $roomTypeSlug): array
    {
        $room = $this->roomCatalog()[$roomTypeSlug] ?? null;

        if (! $room) {
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
}

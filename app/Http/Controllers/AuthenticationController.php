<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Booking;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthenticationController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.sign-in');
    }

    public function showSignupForm()
    {
        return view('auth.sign-up');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>  Hash::make($request->password),
            'is_google_user' => false,
        ]);

        $user->assignRole('client');

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'google' => 'Unable to login with Google. Please try again.',
            ]);
        }

        $isNewUser = false;

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $isNewUser = true;

            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(Str::random(24)),
                'is_google_user' => true,
                'has_changed_password' => false,
                'first_google_login_at' => now(),
            ]);
        } else {
            $updateData = [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'is_google_user' => true,
            ];

            if (!$user->first_google_login_at) {
                $updateData['first_google_login_at'] = now();
            }

            $user->update($updateData);
        }

        if ($isNewUser) {
            $user->assignRole('client');
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }


    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Prevent abuse (optional but recommended)
        if (!$user || !$user->is_google_user) {
            return redirect()->route('dashboard');
        }

        $user->password = Hash::make($request->password);
        $user->has_changed_password = true;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Password updated successfully!');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    //sign in with email and password and passing of data for bookings
    public function loginWithBooking(Request $request)
    {
        // ── 1. Validate credentials + every booking field ──────────────────
        $request->validate([
            // Auth
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],

            // Booking — must match migration columns
            'room_type'            => ['required', 'string'],
            'check_in' => [
                'required',
                'date',
                'after_or_equal:today'
            ],

            'check_out' => [
                'required',
                'date',
                'after:check_in'
            ],
            'number_of_guests'     => ['required', 'integer', 'min:1'],
            'room_price'           => ['required', 'numeric', 'min:0'],
            'micro_pricing_amount' => ['required', 'numeric', 'min:0'],
            'total_price'          => ['required', 'numeric', 'min:0'],
        ]);

        // dd($request->all());

        // ── 2. Attempt login ───────────────────────────────────────────────
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        // // ── 3. Store the valid ID file ─────────────────────────────────────
        // // Stored in storage/app/private/valid_ids/{user_id}/
        // $validIdPath = $request->file('valid_id_path')
        //     ->store('valid_ids/' . Auth::id(), 'private');

        // ── 4. Create the booking ──────────────────────────────────────────
        $booking = Booking::create([
            'user_id'              => Auth::id(),
            'reference_number'     => $this->generateReference(),
            'room_type'            => $request->room_type,
            'check_in' => Carbon::parse(
                $request->check_in
            )->format('Y-m-d'),

            'check_out' => Carbon::parse(
                $request->check_out
            )->format('Y-m-d'),

            'number_of_guests'     => $request->number_of_guests,
            'room_price'           => $request->room_price,
            'micro_pricing_amount' => $request->micro_pricing_amount,
            'total_price'          => $request->total_price,
            'special_requests'     => $request->special_requests,
            'status'               => 'pending',
            'expires_at'           => now()->addHours(24),
        ]);

        // ── 5. Redirect to booking confirmation page ───────────────────────
        return redirect()
            ->route('booking.confirmation', $booking->reference_number)
            ->with('success', 'Booking submitted! We will verify your ID and confirm shortly.');
    }

    // ── Private helpers ────────────────────────────────────────────────────

    /**
     * Generate a unique, human-readable reference number.
     * Format: CH-XXXXXXXX  (CH = Caree Hotel)
     */
    private function generateReference(): string
    {
        do {
            $ref = 'CH-' . strtoupper(Str::random(8));
        } while (Booking::where('reference_number', $ref)->exists());

        return $ref;
    }
}

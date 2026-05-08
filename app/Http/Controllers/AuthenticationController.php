<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
}
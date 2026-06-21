<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('pages.profile', [
            'title' => 'Profile',
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            // 'avatar' => 'nullable|image|max:2048',
            'valid_id' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar_cropped' => ['nullable', 'string'],
        ]);

        // if ($request->hasFile('avatar')) {
        //     $avatarFile = $request->file('avatar');
        //     $filename = $user->id . '_avatar_profile.' . $avatarFile->getClientOriginalExtension();
        //     $destinationPath = public_path('avatars');
        //     if (!file_exists($destinationPath)) {
        //         mkdir($destinationPath, 0755, true);
        //     }
        //     $avatarFile->move($destinationPath, $filename);
        //     $validated['avatar'] = 'avatars/' . $filename;
        // }

        if ($request->filled('avatar_cropped')) {
            $data   = $request->input('avatar_cropped');
            $image  = preg_replace('/^data:image\/\w+;base64,/', '', $data);
            $binary = base64_decode($image);

            $filename = 'avatars/' . $user->id . '_' . time() . '.jpg';
            Storage::disk('public')->put($filename, $binary);

            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $filename;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->fill(collect($validated)->except('password')->toArray());
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
}
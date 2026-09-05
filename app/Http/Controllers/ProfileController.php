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
        $user = Auth::user();
        $user->load('idVerification');
        $valid_id_status = $user->idVerification?->valid_id_status ?? 'pending';
        $idVerificationRemarks = $user->idVerification?->remarks;

        return view('pages.profile', [
            'title' => 'Profile',
            'user' => $user,
            'valid_id_status' => $valid_id_status,
            'idVerificationRemarks' => $idVerificationRemarks,
        ]);
    }
    public function update(Request $request)
    {
        $user = Auth::user();

        $currentIdStatus = strtolower($user->idVerification?->valid_id_status ?? 'pending');
        $canUpdateValidId = in_array($currentIdStatus, ['pending', 'rejected']);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar_cropped' => ['nullable', 'string'],
            'valid_id_upload' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        if ($request->hasFile('valid_id_upload')) {
            if (! $canUpdateValidId) {
                return back()->withErrors([
                    'valid_id_upload' => 'You cannot update your valid ID while it is already verified.',
                ])->withInput();
            }

            $file = $request->file('valid_id_upload');
            $extension = $file->getClientOriginalExtension() ?: 'jpg';

            if ($user->valid_id) {
                Storage::disk('public')->delete($user->valid_id);
            }

            $path = $file->storeAs('valid_ids', $user->id . '_' . time() . '.' . $extension, 'public');
            $user->valid_id = $path;

            // Reset verification back to pending so staff sees it in the review queue again
            $user->idVerification()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'valid_id_status' => 'pending',
                    'verified_by'     => null,
                    'verified_at'     => null,
                    'remarks'         => null,
                ]
            );
        }

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

        $user->fill($request->only([
            'name',
            'email',
            'phone',
            'address',
        ]));

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->has_changed_password = true;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {

        $users = User::all()
            ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'phone' => $user->phone,
                'address' => $user->address,
                'avatar' => $user->avatar,
                'status' => $user->status,
            ];
        });

        return view('pages.user-management.user-management', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function addStaff(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // dd($validated);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('staff');

        return redirect()
            ->route('user-management.index')
            ->with('success', 'Staff user created successfully.');
    }

    public function deactivateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = 'inactive';
        $user->save();

        return redirect()->route('user-management.index')->with('success', 'User status updated successfully.');
    }

    public function activateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return redirect()->route('user-management.index')->with('success', 'User status updated successfully.');
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('defaultpassword');
        $user->save();

        return redirect()->route('user-management.index')->with('success', 'User password reset successfully.');
    }

}
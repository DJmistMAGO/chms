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

        $user->assignRole('staff');

        return redirect()->route('user-management')->with('success', 'Staff added successfully.');
    }

    public function deactivateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = 'inactive';
        $user->save();

        return redirect()->route('user-management')->with('success', 'User status updated successfully.');
    }

    public function activateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active';
        $user->save();

        return redirect()->route('user-management')->with('success', 'User status updated successfully.');
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('defaultpassword');
        $user->save();

        return redirect()->route('user-management')->with('success', 'User password reset successfully.');
    }

}

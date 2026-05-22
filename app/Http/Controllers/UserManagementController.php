<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
            ];
        });

        return view('pages.user-management.user-management', compact('users'));
    }
}

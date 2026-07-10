<?php

namespace App\Http\Controllers;

use App\Models\IdVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuestManagementController extends Controller
{
    public function index()
    {
        $guests = User::role('client')
            ->with([
                'idVerification',
                'bookings' => fn ($query) => $query->latest('created_at'),
            ])
            ->paginate(6)
            ->through(function ($guest) {
                $bookings = $guest->bookings->map(fn ($booking) => [
                    'id' => $booking->id,
                    'reference' => $booking->reference_number
                        ?? ('BK-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT)),
                    'room' => $booking->room?->name ?? $booking->room_type ?? 'N/A',
                    'check_in' => optional($booking->check_in_date)->format('M d, Y'),
                    'check_out' => optional($booking->check_out_date)->format('M d, Y'),
                    'status' => $booking->status,
                    'total_amount' => $booking->total_amount,
                ]);

                $upcoming = $guest->bookings
                    ->whereIn('status', ['Confirmed', 'Booked', 'Pending'])
                    ->filter(function ($booking) {
                        return $booking->check_in_date &&
                            $booking->check_in_date->gte(Carbon::today());
                    })
                    ->sortBy('check_in_date')
                    ->first();

                return [
                    'id' => $guest->id,
                    'name' => $guest->name,
                    'email' => $guest->email,
                    'roles' => $guest->getRoleNames()->toArray(),
                    'phone' => $guest->phone,
                    'address' => $guest->address,
                    'avatar' => $guest->avatar,
                    'status' => $guest->status,
                    'valid_id' => $guest->valid_id,
                    'valid_id_status' => $guest->idVerification?->valid_id_status ?? 'pending',
                    'bookings_count' => $bookings->count(),
                    'upcoming_booking' => $upcoming ? [
                        'reference' => $upcoming->reference_number ?? ('BK-' . str_pad($upcoming->id, 5, '0', STR_PAD_LEFT)),
                        'check_in' => optional($upcoming->check_in_date)->format('M d, Y'),
                        'check_out' => optional($upcoming->check_in_date)->format('M d, Y'),
                        'status' => $upcoming->status,
                    ] : null,
                    'bookings' => $bookings->values(),
                ];
            });

        return view('pages.guest-management.guest-management', compact('guests'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',

        ]);

        $guest = User::findOrFail($id);
        $guest->update($validated);

        return redirect()->back()->with('success', 'User updated successfully.');
    }


    public function deactivateStatus(Request $request, $id)
    {
        $guest = User::findOrFail($id);
        $guest->status = 'inactive';
        $guest->save();

        return redirect()->route('guest-management.index')->with('success', 'User status updated successfully.');
    }

    public function activateStatus(Request $request, $id)
    {
        $guest = User::findOrFail($id);
        $guest->status = 'active';
        $guest->save();

        return redirect()->route('guest-management.index')->with('success', 'User status updated successfully.');
    }

    public function resetPassword(Request $request, $id)
    {
        $guest = User::findOrFail($id);
        $guest->password = Hash::make('defaultpassword');
        $guest->save();

        return redirect()->route('guest-management.index')->with('success', 'User password reset successfully.');
    }

    public function verifyValidId(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:verified,rejected',
            'remarks' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);

        $verification = IdVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'valid_id_status' => $request->status,
                'verified_by'     => auth()->id(),
                'verified_at'     => now(),
                'remarks'         => $request->remarks,
            ]
        );

        return redirect()
            ->back()
            ->with('success', "Guest's ID has been marked as {$request->status}.");
    }
}

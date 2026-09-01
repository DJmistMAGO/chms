<?php

namespace App\Http\Controllers;

use App\Mail\IdVerificationStatusEmail;
use App\Models\IdVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class GuestManagementController extends Controller
{
    public function index()
    {
        $activeBookingStatuses = ['pending', 'confirmed', 'booked', 'checked in', 'checked_in', 'verified'];

        $guests = User::role('client')
            ->with([
                'idVerification',
                'bookings' => fn ($query) => $query->latest('check_in'),
            ])
            ->get()
            ->filter(function ($guest) use ($activeBookingStatuses) {
                return $guest->bookings->filter(function ($booking) use ($activeBookingStatuses) {
                    $status = strtolower(trim((string) ($booking->status ?? '')));

                    if ($status === '' || in_array($status, ['completed', 'cancelled', 'archived'], true)) {
                        return false;
                    }

                    return in_array($status, $activeBookingStatuses, true);
                })->isNotEmpty();
            })
            ->map(function ($guest) use ($activeBookingStatuses) {
                $visibleBookings = $guest->bookings->filter(function ($booking) use ($activeBookingStatuses) {
                    $status = strtolower(trim((string) ($booking->status ?? '')));

                    if ($status === '' || in_array($status, ['completed', 'cancelled', 'archived'], true)) {
                        return false;
                    }

                    return in_array($status, $activeBookingStatuses, true);
                });

                $bookings = $visibleBookings->map(fn ($booking) => [
                    'id' => $booking->id,
                    'reference' => $booking->reference_number
                        ?? ('BK-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT)),
                    'room' => $booking->room?->name ?? $booking->room_type ?? 'N/A',
                    'check_in' => optional($booking->check_in)->format('M d, Y'),
                    'check_out' => optional($booking->check_out)->format('M d, Y'),
                    'status' => $booking->status,
                    'total_amount' => $booking->total_amount,
                ]);

                $upcoming = $visibleBookings
                    ->filter(function ($booking) {
                        $status = strtolower(trim((string) ($booking->status ?? '')));
                        return in_array($status, ['confirmed', 'booked', 'pending', 'checked in', 'checked_in', 'verified'], true)
                            && $booking->check_in
                            && $booking->check_in->gte(Carbon::today());
                    })
                    ->sortBy('check_in')
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
                        'reference' => $upcoming->reference_number ?? ('CH-' . str_pad($upcoming->id, 5, '0', STR_PAD_LEFT)),
                        'check_in' => optional($upcoming->check_in)->format('M d, Y'),
                        'check_out' => optional($upcoming->check_out)->format('M d, Y'),
                        'status' => $upcoming->status,
                    ] : null,
                    'bookings' => $bookings->values(),
                ];
            })
            ->values();

        $perPage = 6;
        $currentPage = max(1, (int) request('page', 1));
        $pageItems = $guests->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $guests = new LengthAwarePaginator(
            $pageItems,
            $guests->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $totalGuests = $guests->total();
        $activeGuests = $guests->getCollection()->filter(fn ($guest) => strtolower((string) ($guest['status'] ?? '')) === 'active')->count();
        $totalBookings = $guests->getCollection()->sum(fn ($guest) => (int) ($guest['bookings_count'] ?? 0));
        $totalRevenue = $guests->getCollection()->sum(function ($guest) {
            return collect($guest['bookings'])->sum(fn ($booking) => (float) ($booking['total_amount'] ?? 0));
        });

        return view('pages.guest-management.guest-management', compact('guests', 'totalGuests', 'activeGuests', 'totalBookings', 'totalRevenue'));
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

        try {
            Mail::to($user->email)->send(new IdVerificationStatusEmail($verification));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->back()
            ->with('success', "Guest's ID has been marked as {$request->status}.");
    }
}

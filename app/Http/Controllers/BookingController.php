<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Room;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {

        return view('pages.chms-features.booking-management.create-booking');
    }

    public function myReservations()
    {
        $user = auth()->user();

        $pendingBookings = Booking::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get()
            ->transform(function ($booking) {
                $booking->check_in = Carbon::parse($booking->check_in);
                $booking->check_out = Carbon::parse($booking->check_out);
                return $booking;
            });

        $confirmedBookings = Booking::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->get()
            ->transform(function ($booking) {
                $booking->check_in = Carbon::parse($booking->check_in);
                $booking->check_out = Carbon::parse($booking->check_out);
                return $booking;
            });

        return view('pages.chms-features.my-reservations.reservation', compact('pendingBookings', 'confirmedBookings'));
    }

    public function pending()
    {
        $pendingBookings = Booking::where('status', 'pending')
            ->latest()
            ->paginate(15);

        $availableRooms = Room::where('status', 'available')
            ->orderBy('room_type')
            ->orderBy('floor')
            ->orderBy('room_no')
            ->get();

        return view('pages.chms-features.booking-management.pending-booking', compact('pendingBookings', 'availableRooms'));
    }

    public function confirmBooking(Request $request, $selectedRef)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $booking = Booking::where('reference_number', $selectedRef)->firstOrFail();

        // Update the booking status to confirmed and assign the selected room
        $booking->status = 'confirmed';
        $booking->room_id = $request->input('room_id');
        $booking->save();

        // Update the room status to occupied
        $room = Room::find($request->input('room_id'));
        $room->status = 'occupied';
        $room->save();

        return redirect()->route('booking.confirmed')->with('success', 'Booking confirmed successfully.');
    }

    public function confirmed()
    {
        // Fetch all confirmed bookings for the staff
        $confirmedBookings = Booking::where('status', 'confirmed')->get();

        return view('pages.chms-features.booking-management.confirmed-booking', compact('confirmedBookings'));
    }

    public function cancelled()
    {
        // Fetch all cancelled bookings for the staff
        $cancelledBookings = Booking::where('status', 'cancelled')->get();

        return view('pages.chms-features.booking-management.cancelled-booking', compact('cancelledBookings'));
    }
}

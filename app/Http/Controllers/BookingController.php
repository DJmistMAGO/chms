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
        $pendingBookings = Booking::where('status', 'Pending')
            ->latest()
            ->paginate(15);

        $availableRooms = Room::where('status', 'Available')
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
        $booking->status = 'Confirmed';
        $booking->room_id = $request->input('room_id');
        $booking->save();

        // Update the room status to occupied
        $room = Room::find($request->input('room_id'));
        $room->status = 'Occupied';
        $room->save();

        return redirect()->route('booking.confirmed')->with('success', 'Booking confirmed successfully.');
    }

    public function confirmed()
    {
        $confirmedBookings = Booking::where('status', 'Confirmed')
            ->latest()
            ->paginate(15);

        return view('pages.chms-features.booking-management.confirmed-booking', compact('confirmedBookings'));
    }

    public function cancelBooking(Request $request, $selectedRef)
    {
        $booking = Booking::where('reference_number', $selectedRef)->firstOrFail();

        //validation
        $request->validate([
            'remarks' => 'nullable|string|max:255',
        ]);

        // Update the booking status to cancelled
        $booking->status = 'Cancelled';
        $booking->remarks = $request->input('remarks');
        $booking->save();

        // If the booking had an assigned room, update that room's status to available
        if ($booking->room_id) {
            $room = Room::find($booking->room_id);
            if ($room) {
                $room->status = 'Available';
                $room->save();
            }
        }

        return redirect()->route('booking.cancelled')->with('success', 'Booking cancelled successfully.');
    }

    public function checkedInBookings()
    {
        $checkedInBookings = Booking::where('status', 'Checked In')
            ->latest()
            ->paginate(15);

        return view('pages.chms-features.booking-management.checkedin-booking', compact('checkedInBookings'));
    }

    public function bookingHistory()
    {
        $bookingHistory = Booking::whereIn('status', ['Cancelled', 'Completed', 'Archived'])
        ->latest()
        ->paginate(15);

        return view('pages.chms-features.booking-management.booking-history', compact('bookingHistory'));
    }

}

<?php

namespace App\Traits;

use App\Models\Booking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;              // 👈 add this
use Illuminate\Validation\ValidationException;


trait HandlesBookingCreation
{
    /**
     * Validation rules for booking fields.
     */
    protected function bookingFieldRules(): array
    {
        return [
            'room_type'           => ['required', 'string'],
            'check_in'            => ['required', 'date', 'after_or_equal:today'],
            'check_out'           => ['required', 'date', 'after:check_in'],
            'number_of_guests'    => ['required', 'integer', 'min:1'],
            'nights'              => ['required', 'integer', 'min:1'],
            'floor_level'         => ['required', 'string'],
            'ambiance'            => ['required', 'string'],
            'food_package'        => ['required', 'string'],
            'room_price'          => ['required', 'numeric', 'min:0'],
            'micro_pricing_amount' => ['required', 'numeric', 'min:0'],
            'total_price'         => ['required', 'numeric', 'min:0'],
            'valid_id_path'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    /**
     * The booking input keys (excluding auth fields like email/password).
     */
    protected function bookingDataKeys(): array
    {
        return [
            'room_type',
            'check_in',
            'check_out',
            'number_of_guests',
            'nights',
            'floor_level',
            'ambiance',
            'food_package',
            'room_price',
            'micro_pricing_amount',
            'total_price',
            'valid_id_path',
        ];
    }


    protected function validateBookingFields(array $data): array
    {
        $rules = $this->bookingFieldRules();

        $applicable = array_intersect_key($rules, $data);

        return Validator::make($data, $applicable)
            ->validate();
    }




    protected function persistBooking( array $validated, int $userId, $uploadedFile = null,
    ?\App\Models\User $user = null   // 👈 added
    ): Booking {
        $validIdPath = null;

        if ($uploadedFile && $uploadedFile->isValid()) {
            $validIdPath = $uploadedFile->store('booking-ids', 'public');
        }  elseif (!empty($validated['valid_id_temp_path'])) {
            $from     = $validated['valid_id_temp_path'];
            $filename = basename($from);
            $to       = 'booking-ids/' . $filename;

            \Log::info('Google booking temp ID move', [
                'from'   => $from,
                'to'     => $to,
                'exists' => Storage::disk('public')->exists($from),
            ]);

            if (Storage::disk('public')->exists($from)) {
                Storage::disk('public')->move($from, $to);
                $validIdPath = $to;
            }
        }

    // also log this
    \Log::info('persistBooking validIdPath', ['validIdPath' => $validIdPath, 'user' => $user?->id]);

    // 👇 sync to users table if user is provided
    if ($validIdPath && $user) {
        $user->update(['valid_id' => $validIdPath]);
    }

    return Booking::create([
        'user_id'              => $userId,
        'reference_number'     => 'CH-' . strtoupper(Str::random(8)),
        'room_type'            => $validated['room_type'],
        'check_in'             => $validated['check_in'],
        'check_out'            => $validated['check_out'],
        'number_of_guests'     => $validated['number_of_guests'],
        'nights'               => $validated['nights'],
        'floor_level'          => $validated['floor_level'],
        'ambiance'             => $validated['ambiance'],
        'food_package'         => $validated['food_package'],
        'room_price'           => $validated['room_price'],
        'micro_pricing_amount' => $validated['micro_pricing_amount'],
        'total_price'          => $validated['total_price'],
        'valid_id_path'        => $validIdPath,
        'status'               => 'pending',
        'expires_at'           => Carbon::parse($validated['check_in'])->addHours(24), // 👈 added
    ]);
}
}

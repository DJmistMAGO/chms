<?php

namespace App\Http\Controllers;

use App\Models\IdVerification;
use App\Models\User;
use Illuminate\Http\Request;

class IdVerificationController extends Controller
{
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

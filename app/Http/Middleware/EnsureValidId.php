<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureValidId
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->hasAnyRole(['admin', 'staff']) || $user->hasValidID()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Please upload a valid ID before making a reservation.',
                'redirect' => route('profile'),
            ], 403);
        }

        return redirect()->route('profile')->withErrors([
            'valid_id_upload' => 'Please upload a valid ID before making a reservation.',
        ]);
    }
}

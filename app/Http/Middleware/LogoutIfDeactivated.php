<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutIfDeactivated
{
    /**
     * Handle an incoming request.
     * If the authenticated user's status is 'inactive', log them out
     * and redirect to the login page with an error message.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && isset($user->status) && $user->status === 'inactive') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'deactivated' => 'Your account has been deactivated by an administrator. Please contact support for help.',
            ]);
        }

        return $next($request);
    }
}

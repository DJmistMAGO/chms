@php
    $user = auth()->user();
    $showWarning = $user && $user->is_google_user && ! $user->has_changed_password && $user->first_google_login_at && $user->first_google_login_at->gt(now()->subDays(1));
@endphp

@if ($showWarning)
    <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-amber-950 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-100" role="alert">
        <strong class="font-semibold">Action required:</strong>
        <span class="block sm:inline">Your account was created with Google. Set a password to keep your account secure—<a href="{{ route('profile') }}" class="font-medium underline decoration-amber-500 underline-offset-2 hover:text-amber-700 dark:decoration-amber-400 dark:hover:text-amber-200">update it in your profile</a>.</span>
    </div>
@endif

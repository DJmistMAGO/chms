@php
    $user = auth()->user();
    $showWarning = $user && $user->is_google_user && ! $user->has_changed_password && $user->first_google_login_at && $user->first_google_login_at->gt(now()->subDays(1));
@endphp

@if ($showWarning)
    <div class="bg-blue-100 dark:bg-gradient-to-br from-amber-300 to-yellow-200 border border-blue-400 dark:border-amber-400 text-black dark:text-black px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Welcome!</strong>
        <span class="block sm:inline">It looks like you signed in with Google. For security reasons, please update your password by <a href="{{ route('profile') }}" class="underline text-blue-400 dark:text-blue-400">clicking here</a>.</span>
    </div>
@endif

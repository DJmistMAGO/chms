<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MicroPricingController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;


// Routes accessible only to guests (not authenticated users)
Route::middleware('guest')->group(function () {

    Route::get('/', function () {
        return view('landingpage');
    })->name('landingpage');

    // Route::get('/customized', function () {
    //     return view('micro-pricing');
    // })->name('customized');

    Route::controller(MicroPricingController::class)
        ->prefix('customize')
        ->group(function () {
            Route::get('/{roomType}', 'booking')->name('customize.booking');
    });

    Route::controller(AuthenticationController::class)
        ->prefix('login')
        ->group(function () {
            Route::get('/', 'showLoginForm')->name('login');
            Route::post('/', 'login')->name('login.post');
            Route::get('/google', 'redirectToGoogle')->name('login.google');
            Route::get('/google/callback', 'handleGoogleCallback')->name('login.google.callback');
    });


    Route::get('/signup', [AuthenticationController::class, 'showSignupForm'])->name('signup');
    Route::post('/signup', [AuthenticationController::class, 'signup'])->name('signup.post')->middleware('web');

});

// Routes accessible only to authenticated users
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management');
});

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});



// to clean

Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages
Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');

Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');


// end of to clean



// test pages

Route::fallback(function () {
    return response()->view('pages.errors.error-404', [], 404);
});
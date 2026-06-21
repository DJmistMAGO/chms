<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MicroPricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomController;
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
            Route::post('/loginWithBooking', 'loginWithBooking')->name('login.with.booking');
    });


    Route::get('/signup', [AuthenticationController::class, 'showSignupForm'])->name('signup');
    Route::post('/signup', [AuthenticationController::class, 'signup'])->name('signup.post')->middleware('web');

});

// Routes accessible only to authenticated users
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard/{referenceNumber?}', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

    Route::controller(BookingController::class)
        ->prefix('booking')
        ->group(function () {
            Route::get('/create', 'create')->name('booking.create');
            Route::get('/pending', 'pending')->name('booking.pending');
            Route::get('/confirmed', 'confirmed')->name('booking.confirmed');
            Route::get('/history', 'bookingHistory')->name('booking.history');
            Route::get('/my-reservations', 'myReservations')->name('booking.my-reservations');
            Route::get('/checked-in', 'checkedInBookings')->name('booking.checkin');
            Route::put('/confirm/{referenceNumber}', 'confirmBooking')->name('booking.confirm');
            Route::put('/cancel/{referenceNumber}', 'cancelBooking')->name('booking.cancel');
        });

    Route::controller(RoomController::class)
        ->prefix('room-management')
        ->group(function () {
            Route::get('/index', 'index')->name('room.index');
            Route::put('/{room}', 'updateStatus')->name('room.updateStatus');
        });

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management.index');
    Route::post('/user-management/addStaff', [UserManagementController::class, 'addStaff'])->name('user-management.addStaff');
    Route::post('/user-management/{id}', [UserManagementController::class, 'update'])->name('user-management.update');
    Route::post('/user-management/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('user-management.reset-password');
    Route::post('/user-management/{id}/activate', [UserManagementController::class, 'activateStatus'])->name('user-management.activate');
    Route::post('/user-management/{id}/deactivate', [UserManagementController::class, 'deactivateStatus'])->name('user-management.deactivate');
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


// pages
Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');




// end of to clean




Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

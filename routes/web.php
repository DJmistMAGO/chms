<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestManagementController;
use App\Http\Controllers\MicroPricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
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

    // Route::controller(MicroPricingController::class)
    //     ->prefix('customize')
    //     ->group(function () {
    //         Route::get('/{roomType}', 'booking')->name('customize.booking');
    // });


    // Route::get('/micro-pricing/{roomType}', [MicroPricingController::class, 'booking'])->name('micro.pricing');
    // Route::post('/login-with-booking', [MicroPricingController::class, 'loginOrRegisterWithBooking'])->name('login.with.booking');
    // Route::post('/booking/google/store', [MicroPricingController::class, 'storeGoogleBookingSession'])->name('booking.google.store');
    // Route::get('/booking/google/redirect', function () {
    //     return redirect()->route('login.google');
    // })->name('booking.google.redirect');

    // Route::controller(AuthenticationController::class)
    //     ->prefix('login')
    //     ->group(function () {
    //         Route::get('/', 'showLoginForm')->name('login');
    //         Route::post('/', 'login')->name('login.post');
    //         Route::get('/google', 'redirectToGoogle')->name('login.google');
    //         Route::get('/google/callback', 'handleGoogleCallback')->name('login.google.callback');
    //         Route::post('/loginWithBooking', 'loginWithBooking')->name('login.with.booking');
    // });

    Route::controller(MicroPricingController::class)
    ->prefix('customize')
    ->group(function () {
        Route::get('/{roomType}', 'booking')->name('customize.booking');
        Route::post('/login-with-booking', 'loginOrRegisterWithBooking')->name('customize.login.with.booking');
        Route::post('/booking/google/store', 'storeGoogleBookingSession')->name('booking.google.store');
    });

    Route::get('/booking/google/redirect', function () {
        return redirect()->route('login.google');
    })->name('booking.google.redirect');

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
            Route::put('/activate/{referenceNumber}', 'checkInActivate')->name('booking.activate');
            Route::put('/early-checkout/{referenceNumber}', 'earlyCheckout')->name('booking.early-checkout');
            Route::put('/cancel/{referenceNumber}', 'cancelBooking')->name('booking.cancel');
            Route::delete('/delete/{referenceNumber}', 'deleteBooking')->name('booking.delete');
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

Route::middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/guest-management', [GuestManagementController::class, 'index'])->name('guest-management.index');
    Route::post('/guest-management/{id}', [GuestManagementController::class, 'update'])->name('guest-management.update');
    Route::post('/guest-management/{id}/reset-password', [GuestManagementController::class, 'resetPassword'])->name('guest-management.reset-password');
    Route::post('/guest-management/{id}/activate', [GuestManagementController::class, 'activateStatus'])->name('guest-management.activate');
    Route::post('/guest-management/{id}/deactivate', [GuestManagementController::class, 'deactivateStatus'])->name('guest-management.deactivate');
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

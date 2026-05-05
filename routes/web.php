<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('landingpage');
})->name('landingpage');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
    })->name('dashboard');
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

// Route::get('/error-404', function () {
//     return view('pages.errors.error-404', ['title' => 'Error 404']);
// })->name('error-404');

Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// Route::get('/signin', function () {
//     return view('pages.auth.signin', ['title' => 'Sign In']);
// })->name('signin');

// Route::get('/signup', function () {
//     return view('pages.auth.signup', ['title' => 'Sign Up']);
// })->name('signup');

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


////////////////// end



// test pages

Route::fallback(function () {
    return response()->view('pages.errors.error-404', [], 404);
});

Route::get('/test', function () {
    return view('test_pages.testlogin');
})->name('test');

// login route (use this format for routing to controllers)
Route::controller(LoginController::class)
    ->prefix('login')
    ->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->name('login.post');
        Route::get('/google', 'redirectToGoogle')->name('login.google');
        Route::get('/google/callback', 'handleGoogleCallback')->name('login.google.callback');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/signup', [LoginController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [LoginController::class, 'signup'])->name('signup.post')->middleware('web');

// ->middleware('guest'); // Optional: Add guest middleware to prevent authenticated users from accessing the login page
// ->middleware('auth'); // Optional: Add auth middleware to protect the dashboard route
// ->middleware('verified'); // Optional: Add email verification middleware if you require email verification for users

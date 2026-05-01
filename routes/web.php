<?php

use Illuminate\Support\Facades\Route;

// display my landing page
Route::get('/', function () {
    return view('landingpage');
});

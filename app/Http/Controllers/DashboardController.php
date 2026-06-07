<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index($referenceNumber = null)
    {

        return view('pages.dashboard.dashboard', ['title' => 'Caree Hotel', 'referenceNumber' => $referenceNumber]);
    }
}

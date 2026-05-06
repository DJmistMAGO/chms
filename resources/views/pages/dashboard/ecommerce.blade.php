@extends('layouts.app')

@section('content')

    @php
        $showWarning = auth()->user()->is_google_user
            && !auth()->user()->has_changed_password
            && auth()->user()->first_google_login_at
            && auth()->user()->first_google_login_at->gt(now()->subDays(1));
    @endphp

    @if($showWarning)
        <div class="bg-blue-100 border border-blue-400 text-black px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Welcome!</strong>
            <span class="block sm:inline">It looks like you signed in with Google. For security reasons, please update your password by <a href="{{--  --}}" class="underline">clicking here</a>.</span>
        </div>
    @endif


    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 space-y-6 xl:col-span-7">
        <x-ecommerce.ecommerce-metrics />
        <x-ecommerce.monthly-sale />
        </div>
        <div class="col-span-12 xl:col-span-5">
            <x-ecommerce.monthly-target />
        </div>

        <div class="col-span-12">
        <x-ecommerce.statistics-chart />
        </div>

        <div class="col-span-12 xl:col-span-5">
        <x-ecommerce.customer-demographic />
        </div>

        <div class="col-span-12 xl:col-span-7">
        <x-ecommerce.recent-orders />
        </div>
    </div>
@endsection

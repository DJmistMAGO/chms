@extends('layouts.authenticated.app')

@push('styles')

@endpush


@section('content')

    <x-dashboard.google-warning />

    {{-- add success and error message --}}
    @if (session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif


    @role('client')
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <x-dashboard.welcome-card :bookingStats="$bookingStats"  />
            </div>

            <div class="col-span-12 md:col-span-6">
                <x-dashboard.upcoming-bookings :bookings="$bookings" />
            </div>

            <div class="col-span-12 md:col-span-6">
                <x-dashboard.booking-history :bookings="$bookings" />
            </div>
            <div class="col-span-12">
                <x-dashboard.booking-confirmation-modal :referenceNumber="$referenceNumber" />
            </div>
        </div>
    @endrole

    @unlessrole('client')
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <x-dashboard.staff-summary :rooms="$rooms" :allBookings="$allBookings" :totalRooms="$totalRooms" :availableRooms="$availableRooms" />
            </div>

            <div class="col-span-12">
                <x-dashboard.room-overview :rooms="$rooms" :totalRooms="$totalRooms" />
            </div>

            <x-dashboard.pending-approvals :pendingBookings="$pendingBookings" />

            <div class="col-span-12 md:col-span-6 space-y-6">
                {{-- <x-dashboard.client-quick-actions /> --}}
                <x-dashboard.client-recent-bookings :bookings="$bookings" />
            </div>
        </div>
    @endunlessrole

@endsection

@extends('layouts.authenticated.app')

@push('styles')

@endpush


@section('content')

    <x-dashboard.google-warning />

    @role('client')
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <x-dashboard.welcome-card :bookingStats="$bookingStats" />
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

    @role('staff')
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <x-dashboard.staff-summary :rooms="$rooms" :allBookings="$allBookings" :totalRooms="$totalRooms" :availableRooms="$availableRooms" />
            </div>

            <div class="col-span-12">
                <x-dashboard.room-overview :rooms="$rooms" :totalRooms="$totalRooms" />
            </div>

            <x-dashboard.pending-approvals :pendingBookings="$pendingBookings" />

            <div class="col-span-12 md:col-span-6 space-y-6">
                <x-dashboard.client-quick-actions />
                <x-dashboard.client-recent-bookings :bookings="$bookings" />
            </div>
        </div>
    @endrole

    @role('admin')
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
            {{-- <x-ecommerce.customer-demographic /> --}}
            </div>

            <div class="col-span-12 xl:col-span-7">
            {{-- <x-ecommerce.recent-orders /> --}}
            </div>
        </div>
    @endrole
@endsection

<?php

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('shows only clients with non-completed bookings on the guest management page', function () {
    Role::firstOrCreate(['name' => 'staff']);
    Role::firstOrCreate(['name' => 'client']);

    $staff = User::factory()->create();
    $staff->assignRole('staff');

    $completedGuest = User::factory()->create();
    $completedGuest->assignRole('client');

    $activeGuest = User::factory()->create();
    $activeGuest->assignRole('client');

    Booking::factory()->create([
        'user_id' => $completedGuest->id,
        'status' => 'Completed',
        'reference_number' => 'BK-10001',
    ]);

    Booking::factory()->create([
        'user_id' => $activeGuest->id,
        'status' => 'Pending',
        'reference_number' => 'BK-10002',
    ]);

    $this->actingAs($staff)
        ->get(route('guest-management.index'))
        ->assertOk()
        ->assertSee($activeGuest->name)
        ->assertDontSee($completedGuest->name);
});

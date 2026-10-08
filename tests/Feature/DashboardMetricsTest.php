<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
});

it('shows user metrics and an admin heading on the admin dashboard', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $staff = User::factory()->create();
    $staff->assignRole('staff');

    $client = User::factory()->create();
    $client->assignRole('client');

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Admin Dashboard')
        ->assertSee('Total Users')
        ->assertSee('Clients')
        ->assertSee('Staff')
        ->assertSee('Active Accounts')
        ->assertSee('New This Month')
        ->assertSee('Total Bookings')
        ->assertDontSee('Staff Dashboard');
});

it('keeps the existing staff dashboard summary for staff', function () {
    $staff = User::factory()->create();
    $staff->assignRole('staff');

    $this->actingAs($staff)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Staff Dashboard')
        ->assertDontSee('Admin Dashboard')
        ->assertSee('Total Rooms')
        ->assertSee('Bookings Today');
});

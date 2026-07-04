<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('reports that a repeat client with a stored valid id does not need a new upload', function () {
    $user = User::factory()->create([
        'name' => 'Repeat Client',
        'email' => 'repeat@example.com',
        'password' => Hash::make('password123'),
        'valid_id' => 'valid-ids/existing-id.pdf' ?? 'booking-ids/existing-id.jpg',
    ]);

    $response = $this->postJson(route('customize.account.check'), [
        'email' => $user->email,
        'password' => 'password123',
        'use_existing_account' => true,
    ]);

    $response->assertOk()
        ->assertJson([
            'requires_id_upload' => false,
            'mode' => 'existing',
            'has_valid_id' => true,
        ]);
});

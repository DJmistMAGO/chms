<?php

use App\Models\User;

it('redirects clients without a valid id before loading room options', function () {
    $user = User::factory()->create(['valid_id' => null]);

    $response = $this->actingAs($user)->get(route('reservations.booking.rooms'));

    $response->assertRedirect(route('profile'))
        ->assertSessionHasErrors('valid_id_upload');
});

it('returns a json error for clients without a valid id', function () {
    $user = User::factory()->create(['valid_id' => null]);

    $response = $this->actingAs($user)->getJson(route('reservations.booking.rooms'));

    $response->assertForbidden()
        ->assertJsonPath('redirect', route('profile'));
});

it('allows clients with a valid id to load room options', function () {
    $user = User::factory()->create(['valid_id' => 'valid_ids/client-id.jpg']);

    $response = $this->actingAs($user)->getJson(route('reservations.booking.rooms'));

    $response->assertOk()
        ->assertJsonCount(3, 'rooms');
});

<?php

use App\Models\IdVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('stores a valid id when the verification status is pending', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'email' => 'profile@example.com',
    ]);

    IdVerification::create([
        'user_id' => $user->id,
        'valid_id_status' => 'pending',
    ]);

    $file = UploadedFile::fake()->image('valid-id.png', 800, 600);

    $response = $this->actingAs($user)
        ->put(route('profile.update'), [
            'name' => 'Jane Doe',
            'email' => 'profile2@example.com',
            'phone' => '09171234567',
            'address' => 'Test Address',
            'valid_id_upload' => $file,
        ]);

    $response->assertRedirect(route('profile'));

    $user->refresh();

    expect($user->valid_id)->toBeString()
        ->and($user->valid_id)->toContain('valid_ids/')
        ->and(Storage::disk('public')->exists($user->valid_id))->toBeTrue();
});

it('does not replace a valid id when the verification status is verified', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'email' => 'verified@example.com',
        'valid_id' => 'valid_ids/existing-id.jpg',
    ]);

    IdVerification::create([
        'user_id' => $user->id,
        'valid_id_status' => 'verified',
    ]);

    $file = UploadedFile::fake()->image('new-valid-id.png', 800, 600);

    $response = $this->actingAs($user)
        ->put(route('profile.update'), [
            'name' => 'Jane Doe',
            'email' => 'verified2@example.com',
            'phone' => '09171234567',
            'address' => 'Test Address',
            'valid_id_upload' => $file,
        ]);

    $response->assertSessionHasErrors('valid_id_upload');

    $user->refresh();

    expect($user->valid_id)->toBe('valid_ids/existing-id.jpg');
});

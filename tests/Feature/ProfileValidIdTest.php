<?php

use App\Models\IdVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

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

it('updates profile columns and marks a changed password', function () {
    $user = User::factory()->create([
        'email' => 'profile-fields@example.com',
        'has_changed_password' => false,
    ]);

    $response = $this->actingAs($user)
        ->put(route('profile.update'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '09171234567',
            'address' => 'Updated Address',
            'password' => 'NewPassword1!',
            'password_confirmation' => 'NewPassword1!',
        ]);

    $response->assertRedirect(route('profile'));

    $user->refresh();

    expect($user->name)->toBe('Updated Name')
        ->and($user->email)->toBe('updated@example.com')
        ->and($user->phone)->toBe('09171234567')
        ->and($user->address)->toBe('Updated Address')
        ->and($user->has_changed_password)->toBe(1)
        ->and(Hash::check('NewPassword1!', $user->password))->toBeTrue();
});

it('rejects passwords that do not meet the profile password policy', function () {
    $user = User::factory()->create([
        'email' => 'password-policy@example.com',
        'password' => Hash::make('OriginalPassword1!'),
    ]);

    foreach (
        [
            'Ab1!xxx',
            'abcdefg1!',
            'ABCDEFG1!',
            'Abcdefgh!',
            'Abcdefg1',
            'Abcdefg1?',
        ] as $invalidPassword
    ) {
        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $invalidPassword,
                'password_confirmation' => $invalidPassword,
            ])
            ->assertSessionHasErrors('password');
    }

    expect(Hash::check('OriginalPassword1!', $user->refresh()->password))->toBeTrue();
});

it('rejects a profile password when confirmation does not match', function () {
    $user = User::factory()->create([
        'email' => 'password-confirmation@example.com',
        'password' => Hash::make('OriginalPassword1!'),
    ]);

    $this->actingAs($user)
        ->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'NewPassword1!',
            'password_confirmation' => 'DifferentPassword1!',
        ])
        ->assertSessionHasErrors('password');

    expect(Hash::check('OriginalPassword1!', $user->refresh()->password))->toBeTrue();
});

it('accepts Philippine phone formats and rejects invalid phone numbers', function () {
    $user = User::factory()->create([
        'email' => 'phone-format@example.com',
    ]);

    $this->actingAs($user)
        ->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '+639171234567',
        ])
        ->assertRedirect(route('profile'));

    expect($user->refresh()->phone)->toBe('+639171234567');

    foreach (['0917123456', '08171234567', '+63917123456', '+6391712345678'] as $invalidPhone) {
        $this->put(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $invalidPhone,
        ])->assertSessionHasErrors('phone');
    }
});

it('prevents an admin from modifying their profile', function () {
    Role::create(['name' => 'admin']);
    $user = User::factory()->create([
        'name' => 'Protected Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('OriginalPassword1!'),
    ]);
    $user->assignRole('admin');

    $response = $this->actingAs($user)
        ->put(route('profile.update'), [
            'name' => 'Changed Admin',
            'email' => 'changed-admin@example.com',
            'phone' => '09170000000',
            'address' => 'Changed Address',
            'password' => 'ChangedPassword1!',
            'password_confirmation' => 'ChangedPassword1!',
        ]);

    $response->assertRedirect(route('profile'))
        ->assertSessionHasErrors('profile');

    $user->refresh();

    expect($user->name)->toBe('Protected Admin')
        ->and($user->email)->toBe('admin@example.com')
        ->and($user->phone)->not->toBe('09170000000')
        ->and(Hash::check('OriginalPassword1!', $user->password))->toBeTrue()
        ->and(Hash::check('ChangedPassword1!', $user->password))->toBeFalse();
});

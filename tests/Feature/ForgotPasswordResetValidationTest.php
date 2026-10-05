<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

it('rejects reset passwords that do not meet the signup password policy', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);
    $resetUrl = route('password.reset', [
        'token' => $token,
        'email' => $user->email,
    ]);

    foreach ([
        'Ab1!xxx',
        'abcdefg1!',
        'ABCDEFG1!',
        'Abcdefgh!',
        'Abcdefg1',
        'Abcdefg1?',
    ] as $invalidPassword) {
        $this->from($resetUrl)
            ->post(route('password.update'), [
                'token' => $token,
                'email' => $user->email,
                'password' => $invalidPassword,
                'password_confirmation' => $invalidPassword,
            ])
            ->assertRedirect($resetUrl)
            ->assertSessionHasErrors('password');
    }

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

it('resets the password when it meets the signup password policy', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'StrongPass1!',
        'password_confirmation' => 'StrongPass1!',
    ])->assertRedirect(route('login'));

    expect(Hash::check('StrongPass1!', $user->fresh()->password))->toBeTrue();
});

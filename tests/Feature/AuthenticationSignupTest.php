<?php

use App\Models\User;

it('rejects signup passwords that do not meet the password policy', function () {
    foreach ([
        'Ab1!xxx',
        'abcdefg1!',
        'ABCDEFG1!',
        'Abcdefgh!',
        'Abcdefg1',
        'Abcdefg1?',
    ] as $invalidPassword) {
        $this->post(route('signup.post'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => $invalidPassword,
            'password_confirmation' => $invalidPassword,
        ])->assertSessionHasErrors('password');
    }

    expect(User::count())->toBe(0);
});

it('rejects signup email addresses with invalid syntax or a non-resolving domain', function () {
    foreach ([
        'not-an-email',
        'jane@nonexistent.invalid',
    ] as $invalidEmail) {
        $this->post(route('signup.post'), [
            'name' => 'Jane Doe',
            'email' => $invalidEmail,
            'password' => 'StrongPass1!',
            'password_confirmation' => 'StrongPass1!',
        ])->assertSessionHasErrors('email');
    }

    expect(User::count())->toBe(0);
});

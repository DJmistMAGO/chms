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

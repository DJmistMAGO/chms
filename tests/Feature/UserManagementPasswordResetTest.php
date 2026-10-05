<?php

use App\Mail\ForgotPassword;
use App\Models\User;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Symfony\Component\Mailer\Exception\TransportException;
use Spatie\Permission\Models\Role;

it('emails an admin-triggered password reset link without changing the user password', function () {
    Mail::fake();

    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create([
        'password' => 'current-password',
    ]);
    $user->assignRole('staff');
    $originalPassword = $user->password;

    $client = User::factory()->create();
    $client->assignRole('client');

    $managementUrl = route('user-management.index', [
        'user_tab' => 'client',
        'client_page' => 1,
    ]);

    $response = $this->actingAs($admin)
        ->from($managementUrl)
        ->post(route('user-management.reset-password', $user->id));

    $response->assertRedirect($managementUrl)
        ->assertSessionHas('success', "Password reset link sent to {$user->email}.");

    $managementPage = $this->get($managementUrl);
    $managementPage->assertOk()
        ->assertSee('form.dataset.submitting', false)
        ->assertSee('Sending...', false)
        ->assertSee('disabled:cursor-not-allowed disabled:opacity-60', false);

    expect(substr_count($managementPage->getContent(), 'reset-password-spinner hidden'))->toBe(5);

    $allUsersPage = $this->get(route('user-management.index', [
        'user_tab' => 'all',
        'all_page' => 1,
    ]));
    $allUsersPage->assertOk()
        ->assertSee('User type')
        ->assertSee($admin->email);

    $user->refresh();
    expect($user->password)->toBe($originalPassword);

    $resetUrl = null;
    $resetToken = null;
    Mail::assertSent(ForgotPassword::class, function (ForgotPassword $mail) use ($user, &$resetUrl, &$resetToken) {
        $resetUrl = $mail->resetUrl;
        $resetToken = $mail->token;

        return $mail->hasTo($user->email)
            && Password::broker()->tokenExists($user, $mail->token);
    });

    expect($resetUrl)->toBe(route('password.reset', [
        'token' => $resetToken,
        'email' => $user->email,
    ]));
});

it('returns to user management with an error when the mail server rejects a reset email', function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create();
    $user->assignRole('staff');

    $managementUrl = route('user-management.index');
    $pendingMail = Mockery::mock(PendingMail::class);
    $pendingMail->shouldReceive('send')
        ->once()
        ->with(Mockery::type(ForgotPassword::class))
        ->andThrow(new TransportException('Recipient rejected'));

    Mail::shouldReceive('to')
        ->once()
        ->with($user->email)
        ->andReturn($pendingMail);

    $this->actingAs($admin)
        ->from($managementUrl)
        ->post(route('user-management.reset-password', $user->id))
        ->assertRedirect($managementUrl)
        ->assertSessionHasErrors([
            'email' => 'Could not send the password reset email. Verify the address and try again.',
        ]);
});

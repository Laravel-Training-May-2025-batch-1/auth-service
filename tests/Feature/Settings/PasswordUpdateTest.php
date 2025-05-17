<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

test('password can be updated', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);

    $response = $this
        ->actingAs($user)
        ->put('/api/settings/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response->assertNoContent();

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);

    $response = $this
        ->actingAs($user)
        ->put('/api/settings/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response->assertStatus(422);
});

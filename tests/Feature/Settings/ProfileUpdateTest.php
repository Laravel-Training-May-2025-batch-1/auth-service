<?php

use App\Models\User;
use Laravel\Passport\Passport;

test('profile information can be updated', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);

    $response = $this
        ->actingAs($user)
        ->patch('/api/settings/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response->assertNoContent();

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);

    $response = $this
        ->actingAs($user)
        ->patch('/api/settings/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response->assertNoContent();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);

    $response = $this
        ->actingAs($user)
        ->delete('/api/settings/profile', [
            'password' => 'wrong-password',
        ]);

    $response->assertStatus(422);

    expect($user->fresh())->not->toBeNull();
});

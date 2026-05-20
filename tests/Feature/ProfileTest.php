<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('account settings page is displayed', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('account_settings'));

    $response
        ->assertOk()
        ->assertSeeVolt('components.profile.profile_settings');
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('components.profile.profile_settings')
        ->set('fname', 'Updated')
        ->set('lname', 'Name')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->fname)->toBe('Updated');
    expect($user->lname)->toBe('Name');
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('components.profile.profile_contact')
        ->set('email', $user->email)
        ->set('current_password', 'password')
        ->call('updateEmailSubmit')
        ->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('profile.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser')
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('profile.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser')
        ->assertHasErrors('password')
        ->assertNoRedirect();

    $this->assertNotNull($user->fresh());
});

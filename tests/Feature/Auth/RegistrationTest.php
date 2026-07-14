<?php

use App\Models\Page;
use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register and get a page', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'test-user',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    $user = User::firstWhere('email', 'test@example.com');
    expect($user->page)->not->toBeNull()
        ->and($user->page->username)->toBe('test-user');
});

test('registration fails without a username', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

test('registration rejects an already taken username', function () {
    Page::factory()->create(['username' => 'taken']);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'taken',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

test('registration rejects reserved usernames', function (string $username) {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => $username,
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
})->with(['admin', 'dashboard', 'login', 'api']);

test('registration rejects invalid username formats', function (string $username) {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => $username,
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
})->with([
    'With Spaces',
    'UPPERCASE',
    'año-nuevo',
    '-starts-with-hyphen',
    'ends-with-hyphen-',
    'double--hyphen',
    'ab',
    'this-username-is-way-too-long-for-us',
]);

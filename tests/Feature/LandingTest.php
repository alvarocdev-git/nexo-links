<?php

use App\Models\Page;
use App\Models\User;

test('the landing page renders with CTAs for guests', function () {
    Page::factory()->create(['username' => config('nexo.example_username')]);

    $response = $this->get('/');

    $response->assertOk()
        ->assertSee('Your links.')
        ->assertSee(route('register'))
        ->assertSee(route('login'))
        ->assertSee('See a live example')
        ->assertSee(url('/'.config('nexo.example_username')));
});

test('the example button is hidden when the example page does not exist', function () {
    $this->get('/')
        ->assertOk()
        ->assertDontSee('See a live example');
});

test('authenticated users see the dashboard link instead of register', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/')
        ->assertOk()
        ->assertSee(route('dashboard'))
        ->assertDontSee('Log in');
});

test('the landing links to the repository', function () {
    $this->get('/')->assertOk()->assertSee(config('nexo.repository_url'));
});

test('public pages link back home with a create-yours CTA', function () {
    $page = Page::factory()->create();

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertSee('Create yours')
        ->assertSee(route('home'));
});

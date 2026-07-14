<?php

use App\Models\Page;

test('the help page renders every topic', function () {
    $this->get('/help')
        ->assertOk()
        ->assertSee('What can I do in Nexo Links?')
        ->assertSeeInOrder([
            'Create your account',
            'Add and organize your links',
            'Schedule, highlight and countdowns',
            'Contact buttons and WhatsApp',
            'Social icons',
            'Make it yours',
            'Understand your analytics',
            'Share your page',
        ]);
});

test('the help page is translated', function () {
    $this->get('/help?lang=es')->assertOk()->assertSee('¿Qué puedo hacer en Nexo Links?');
    $this->get('/help?lang=pt_BR')->assertOk()->assertSee('O que posso fazer no Nexo Links?');
});

test('the landing links to the help page', function () {
    $this->get('/')->assertOk()->assertSee(route('help'));
});

test('the dashboard menu links to the help page', function () {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->get('/dashboard')->assertOk()->assertSee(route('help'));
});

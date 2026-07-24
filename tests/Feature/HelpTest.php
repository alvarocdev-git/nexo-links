<?php

use App\Models\Page;

test('the help center renders the FAQs', function () {
    $this->get('/help')
        ->assertOk()
        ->assertSee(__('nexo.help.title'))
        ->assertSeeInOrder([
            'How do I create my page?',
            'How do I add and reorder my links?',
            'How do the social icons work?',
            'What do the analytics show, and do they track visitors?',
            'How do I share my page?',
        ]);
});

test('the help center is translated', function () {
    $this->get('/help?lang=es')->assertOk()->assertSee('Centro de ayuda')->assertSee('¿Cómo creo mi página?');
    $this->get('/help?lang=pt')->assertOk()->assertSee('Central de ajuda')->assertSee('Como crio a minha página?');
});

test('the landing links to the help page', function () {
    $this->get('/')->assertOk()->assertSee(route('help'));
});

test('the dashboard menu links to the help page', function () {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->get('/dashboard')->assertOk()->assertSee(route('help'));
});

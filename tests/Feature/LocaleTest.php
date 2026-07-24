<?php

use App\Models\Page;

test('the interface defaults to english', function () {
    $this->get('/')->assertOk()->assertSee('Create your page');
});

test('the lang parameter switches the language and persists in the session', function () {
    $this->get('/?lang=es')->assertOk()->assertSee('Crea tu página');

    // Follow-up request without the parameter keeps the choice.
    $this->get('/')->assertOk()->assertSee('Crea tu página');
});

test('portuguese is available', function () {
    $this->get('/?lang=pt')->assertOk()->assertSee('Crie sua página');
});

test('an unsupported lang parameter is ignored', function () {
    $this->get('/?lang=de')->assertOk()->assertSee('Create your page');
    $this->get('/?lang=../../etc')->assertOk()->assertSee('Create your page');
});

test('public pages pick the visitor browser language', function () {
    $page = Page::factory()->create();

    $this->get('/'.$page->username, ['Accept-Language' => 'es-AR,es;q=0.9'])
        ->assertOk()
        ->assertSee('Crea la tuya');

    $this->get('/'.$page->username, ['Accept-Language' => 'pt-BR,pt;q=0.9'])
        ->assertOk()
        ->assertSee('Crie a sua');
});

test('an explicit choice beats the browser language', function () {
    $this->get('/?lang=en')->assertOk();

    $this->get('/', ['Accept-Language' => 'es-AR'])
        ->assertOk()
        ->assertSee('Create your page');
});

test('validation messages are translated', function () {
    $this->get('/?lang=es');

    $response = $this->post('/register', [
        'name' => 'Test',
        'username' => 'HAS UPPER',
        'email' => 'not-an-email',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertInvalid([
        'email' => 'correo',
        'username' => 'minúsculas',
    ]);
});

test('the language switcher is visible on the landing and dashboard', function () {
    $this->get('/')->assertOk()->assertSee('lang="pt"', false);

    $page = Page::factory()->create();
    $this->actingAs($page->user)->get('/dashboard')->assertOk()->assertSee('lang="es"', false);
});

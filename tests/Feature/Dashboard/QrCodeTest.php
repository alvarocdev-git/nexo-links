<?php

use App\Models\Page;

test('guests cannot get a QR code', function () {
    $this->get('/qr')->assertRedirect('/login');
});

test('the QR endpoint returns an SVG', function () {
    $page = Page::factory()->create();

    $response = $this->actingAs($page->user)->get('/qr');

    $response->assertOk()
        ->assertHeader('Content-Type', 'image/svg+xml');

    expect($response->getContent())->toContain('<svg');
});

test('the download flag sets a file attachment named after the username', function () {
    $page = Page::factory()->create(['username' => 'ada']);

    $this->actingAs($page->user)->get('/qr?download=1')
        ->assertOk()
        ->assertHeader('Content-Disposition', 'attachment; filename="nexo-ada-qr.svg"');
});

test('the dashboard shows the share section', function () {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->get('/dashboard')
        ->assertOk()
        ->assertSee('Share your page')
        ->assertSee(route('qr.show'))
        ->assertSee(route('page.show', $page->username));
});

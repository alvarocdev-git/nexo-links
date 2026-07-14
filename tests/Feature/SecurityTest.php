<?php

use App\Models\Link;
use App\Models\Page;

test('security headers are sent on public pages', function () {
    $page = Page::factory()->create();

    $response = $this->get('/'.$page->username);

    $response->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'DENY')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

    expect($response->headers->get('Content-Security-Policy'))
        ->toContain("default-src 'self'")
        ->toContain("frame-ancestors 'none'")
        ->toContain("object-src 'none'");
});

test('security headers are sent on the dashboard', function () {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->get('/dashboard')
        ->assertOk()
        ->assertHeader('X-Frame-Options', 'DENY')
        ->assertHeaderMissing('X-Powered-By');
});

test('no page loads external fonts or third-party assets', function () {
    $page = Page::factory()->create();

    foreach (['/', '/help', '/login', '/'.$page->username] as $uri) {
        $content = $this->get($uri)->getContent();

        expect($content)
            ->not->toContain('fonts.bunny.net')
            ->not->toContain('fonts.googleapis.com')
            ->not->toContain('cdn.');
    }
});

test('registration is rate limited', function () {
    for ($i = 0; $i < 10; $i++) {
        $this->post('/register', []);
    }

    $this->post('/register', [])->assertTooManyRequests();
});

test('password reset requests are rate limited', function () {
    for ($i = 0; $i < 6; $i++) {
        $this->post('/forgot-password', ['email' => 'someone@example.com']);
    }

    $this->post('/forgot-password', ['email' => 'someone@example.com'])->assertTooManyRequests();
});

test('accessibility basics: skip link, form labels and expanded state', function () {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->get('/dashboard')
        ->assertOk()
        ->assertSee('Skip to content')
        ->assertSee('id="main"', false)
        ->assertSee('aria-label="Platform"', false)
        ->assertSee('aria-label="Country code"', false)
        ->assertSee('aria-expanded', false);
});

test('public pages keep focus-visible styles on interactive elements', function () {
    $page = Page::factory()->create();
    Link::factory()->create(['page_id' => $page->id]);

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertSee('focus-visible:ring-2', false);
});

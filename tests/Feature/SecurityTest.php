<?php

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

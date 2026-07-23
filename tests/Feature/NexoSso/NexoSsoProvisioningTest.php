<?php

// nexolinks-specific integration guard (the FASE 1 blocker): a user owns a
// mandatory 1:1 page, and every owner controller does pageOf() -> abort 404 when
// it is missing. A bare template `newUser()` would create the user but no page,
// so the first SSO login would succeed and then 404 on the whole dashboard. This
// proves the resolver provisions the page and the owner surface actually works.

use App\Models\Page;
use App\Models\User;
use App\Services\NexoSso\NexoSsoUserResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;

require_once __DIR__.'/NexoSsoTestSupport.php';

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config([
        'nexo-sso.enabled' => true,
        'nexo-sso.issuer' => 'https://nexoid.test',
        'nexo-sso.client_id' => '11111111-2222-3333-4444-555555555555',
    ]);
});

test('AC-LINK-1: a fresh SSO login provisions a page and reaches a working dashboard (no 404)', function (): void {
    nexoSsoFakeProvider();

    nexoSsoCallback($this)->assertRedirect(route('dashboard', absolute: false));

    $user = User::query()->where('email', 'user@example.com')->firstOrFail();
    expect($user->page)->not->toBeNull()
        ->and($user->page->username)->toMatch('/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/');

    // The provisioned page makes the whole owner surface reachable, not 404.
    $this->get(route('dashboard'))->assertOk();
    $this->get(route('design.edit'))->assertOk();
    $this->get(route('analytics'))->assertOk();
});

test('generated usernames avoid reserved names and collisions', function (): void {
    // A display name that sanitizes to a reserved handle ("admin") must not be
    // handed out verbatim, and a second identical claim must not collide.
    Page::factory()->create(['username' => 'admin-taken']);

    $resolver = app(NexoSsoUserResolver::class);

    $first = $resolver->resolve([
        'sub' => 'sub-1', 'email' => 'a@example.com', 'email_verified' => true, 'name' => 'admin',
    ]);
    $second = $resolver->resolve([
        'sub' => 'sub-2', 'email' => 'b@example.com', 'email_verified' => true, 'name' => 'admin',
    ]);

    $reserved = config('nexo.reserved_usernames');
    expect($first->page->username)->not->toBeIn($reserved)
        ->and($second->page->username)->not->toBeIn($reserved)
        ->and($first->page->username)->not->toBe($second->page->username);
});

<?php

use Illuminate\Support\Facades\Route;

// Guardian: every registerable single-word top-level GET route segment must be a
// reserved username. Otherwise a user could register that handle and permanently
// shadow their own public page (served by the GET /{username} catch-all) behind
// an app route. Enumerates the live route table so a NEW top-level GET route
// can't silently reopen the gap — the previous check only asserted /login + /register.
test('every registerable top-level GET route segment is a reserved username', function (): void {
    $reserved = config('nexo.reserved_usernames');

    // Mirrors the Username rule: lowercase alnum with single - or _ separators, 3–30 chars.
    $registerable = fn (string $seg): bool => strlen($seg) >= 3
        && strlen($seg) <= 30
        && preg_match('/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/', $seg) === 1;

    $offenders = collect(Route::getRoutes())
        ->filter(fn ($route) => in_array('GET', $route->methods(), true))
        ->map(fn ($route) => explode('/', $route->uri())[0]) // first path segment
        ->reject(fn ($seg) => $seg === '' || str_contains($seg, '{')) // root + the catch-all param
        ->filter($registerable)
        ->reject(fn ($seg) => in_array($seg, $reserved, true))
        ->unique()
        ->values()
        ->all();

    expect($offenders)->toBe([]);
});

<?php

use App\Models\Link;
use App\Models\Page;

test('guests are redirected to login', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('the dashboard lists only the user\'s links', function () {
    $page = Page::factory()->create();
    Link::factory()->create(['page_id' => $page->id, 'title' => 'My own link']);
    Link::factory()->create(['title' => 'Someone else\'s link']);

    $response = $this->actingAs($page->user)->get('/dashboard');

    $response->assertOk()
        ->assertSee('My own link')
        ->assertDontSee('Someone else\'s link');
});

test('a user can create a link appended at the end', function () {
    $page = Page::factory()->create();
    Link::factory()->create(['page_id' => $page->id, 'position' => 0]);

    $response = $this->actingAs($page->user)->post('/links', [
        'title' => 'My portfolio',
        'url' => 'https://example.com',
    ]);

    $response->assertRedirect('/dashboard');
    expect($page->links()->where('title', 'My portfolio')->first())
        ->not->toBeNull()
        ->position->toBe(1);
});

test('link creation rejects unsafe or invalid urls', function (string $url) {
    $page = Page::factory()->create();

    $response = $this->actingAs($page->user)->post('/links', [
        'title' => 'Bad link',
        'url' => $url,
    ]);

    $response->assertSessionHasErrors('url');
    expect(Link::count())->toBe(0);
})->with([
    'javascript:alert(1)',
    'data:text/html,<script>alert(1)</script>',
    'ftp://example.com/file',
    'no-scheme.com',
    'https://',
]);

test('link creation accepts contact schemes', function (string $url) {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->post('/links', [
        'title' => 'Contact me',
        'url' => $url,
    ])->assertSessionHasNoErrors();

    expect(Link::count())->toBe(1);
})->with([
    'https://example.com/profile',
    'mailto:hi@example.com',
    'tel:+5491122334455',
]);

test('a user can update their own link', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->create(['page_id' => $page->id]);

    $response = $this->actingAs($page->user)->patch("/links/{$link->id}", [
        'title' => 'Updated title',
        'url' => 'https://updated.example.com',
    ]);

    $response->assertRedirect('/dashboard');
    expect($link->refresh())
        ->title->toBe('Updated title')
        ->url->toBe('https://updated.example.com');
});

test('a user cannot update someone else\'s link', function () {
    $page = Page::factory()->create();
    $foreignLink = Link::factory()->create(['title' => 'Untouched']);

    $response = $this->actingAs($page->user)->patch("/links/{$foreignLink->id}", [
        'title' => 'Hacked',
    ]);

    $response->assertForbidden();
    expect($foreignLink->refresh()->title)->toBe('Untouched');
});

test('a user can toggle a link\'s visibility', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->create(['page_id' => $page->id, 'is_visible' => true]);

    $this->actingAs($page->user)->patch("/links/{$link->id}", ['is_visible' => 0]);

    expect($link->refresh()->is_visible)->toBeFalse();
});

test('a user can delete their own link', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->create(['page_id' => $page->id]);

    $response = $this->actingAs($page->user)->delete("/links/{$link->id}");

    $response->assertRedirect('/dashboard');
    expect(Link::count())->toBe(0);
});

test('a user cannot delete someone else\'s link', function () {
    $page = Page::factory()->create();
    $foreignLink = Link::factory()->create();

    $this->actingAs($page->user)->delete("/links/{$foreignLink->id}")->assertForbidden();

    expect(Link::count())->toBe(1);
});

test('a user can reorder their links', function () {
    $page = Page::factory()->create();
    $first = Link::factory()->create(['page_id' => $page->id, 'position' => 0]);
    $second = Link::factory()->create(['page_id' => $page->id, 'position' => 1]);
    $third = Link::factory()->create(['page_id' => $page->id, 'position' => 2]);

    $response = $this->actingAs($page->user)->patchJson('/links/reorder', [
        'links' => [$third->id, $first->id, $second->id],
    ]);

    $response->assertNoContent();
    expect($third->refresh()->position)->toBe(0)
        ->and($first->refresh()->position)->toBe(1)
        ->and($second->refresh()->position)->toBe(2);
});

test('reordering rejects links that are not the user\'s', function () {
    $page = Page::factory()->create();
    $own = Link::factory()->create(['page_id' => $page->id, 'position' => 0]);
    $foreign = Link::factory()->create(['position' => 5]);

    $response = $this->actingAs($page->user)->patchJson('/links/reorder', [
        'links' => [$foreign->id, $own->id],
    ]);

    $response->assertUnprocessable();
    expect($foreign->refresh()->position)->toBe(5);
});

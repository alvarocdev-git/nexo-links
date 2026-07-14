<?php

use App\Models\Click;
use App\Models\Link;
use App\Models\Page;

test('visiting a link redirects and records an anonymous click', function () {
    $link = Link::factory()->create(['url' => 'https://example.com/target']);

    $response = $this->get("/l/{$link->id}");

    $response->assertRedirect('https://example.com/target');

    $click = Click::sole();
    expect($click->link_id)->toBe($link->id)
        ->and(strlen($click->visitor_hash))->toBe(64)
        ->and($click->visitor_hash)->not->toContain('127.0.0.1')
        ->and($click->referrer_host)->toBeNull();
});

test('the same visitor gets the same hash within a day and a new one the next day', function () {
    $this->travelTo(now()->startOfDay()->addHours(12));

    $link = Link::factory()->create();

    $this->get("/l/{$link->id}");
    $this->get("/l/{$link->id}");

    $this->travel(1)->days();
    $this->get("/l/{$link->id}");

    $hashes = Click::orderBy('id')->pluck('visitor_hash');
    expect($hashes[0])->toBe($hashes[1])
        ->and($hashes[2])->not->toBe($hashes[0]);
});

test('external referrers are stored as a bare host', function () {
    $link = Link::factory()->create();

    $this->get("/l/{$link->id}", ['Referer' => 'https://www.instagram.com/some/path']);

    expect(Click::sole()->referrer_host)->toBe('instagram.com');
});

test('clicks coming from the app itself count as direct', function () {
    $link = Link::factory()->create();

    $this->get("/l/{$link->id}", ['Referer' => 'http://localhost/'.$link->page->username]);

    expect(Click::sole()->referrer_host)->toBeNull();
});

test('unpublished links return 404 and record nothing', function () {
    $hidden = Link::factory()->hidden()->create();
    $future = Link::factory()->scheduled(now()->addDay())->create();

    $this->get("/l/{$hidden->id}")->assertNotFound();
    $this->get("/l/{$future->id}")->assertNotFound();

    expect(Click::count())->toBe(0);
});

test('guests cannot see analytics', function () {
    $this->get('/analytics')->assertRedirect('/login');
});

test('analytics shows totals, unique visitors and per-link counts', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->create(['page_id' => $page->id, 'title' => 'Tracked link']);
    Click::factory()->count(3)->create(['link_id' => $link->id, 'visitor_hash' => str_repeat('a', 64), 'created_at' => now()]);
    Click::factory()->create(['link_id' => $link->id, 'visitor_hash' => str_repeat('b', 64), 'created_at' => now()]);

    $response = $this->actingAs($page->user)->get('/analytics');

    $response->assertOk()
        ->assertSee('Tracked link')
        ->assertSee('Total clicks')
        ->assertSeeInOrder(['Total clicks', '4'])
        ->assertSeeInOrder(['Unique visitors', '2']);
});

test('analytics only counts the user\'s own links', function () {
    $page = Page::factory()->create();
    Click::factory()->count(5)->create(['created_at' => now()]);

    $response = $this->actingAs($page->user)->get('/analytics');

    $response->assertOk()->assertSeeInOrder(['Total clicks', '0']);
});

test('analytics respects the range and falls back to 30 days on invalid input', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->create(['page_id' => $page->id]);
    Click::factory()->create(['link_id' => $link->id, 'created_at' => now()->subDays(10)]);

    $this->actingAs($page->user)->get('/analytics?range=7')
        ->assertOk()->assertSeeInOrder(['Total clicks', '0']);

    $this->actingAs($page->user)->get('/analytics?range=nonsense')
        ->assertOk()->assertSeeInOrder(['Total clicks', '1']);
});

test('analytics lists top referrers', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->create(['page_id' => $page->id]);
    Click::factory()->count(2)->create(['link_id' => $link->id, 'referrer_host' => 'instagram.com', 'created_at' => now()]);

    $this->actingAs($page->user)->get('/analytics')
        ->assertOk()->assertSee('instagram.com');
});

<?php

use App\Models\Link;
use App\Models\Page;

test('a public page renders username, bio and visible links', function () {
    $page = Page::factory()->create(['username' => 'ada', 'bio' => 'Math & code.']);
    $link = Link::factory()->create(['page_id' => $page->id, 'title' => 'My repo', 'url' => 'https://example.com/repo']);

    $response = $this->get('/ada');

    $response->assertOk()
        ->assertSee('@ada')
        ->assertSee('Math &amp; code.', false)
        ->assertSee('My repo')
        ->assertSee(route('link.visit', $link));
});

test('an unknown username returns 404', function () {
    $this->get('/nobody-here')->assertNotFound();
});

test('usernames with invalid format do not match the route', function () {
    $this->get('/No%20Valid')->assertNotFound();
});

test('hidden links are not rendered', function () {
    $page = Page::factory()->create();
    Link::factory()->hidden()->create(['page_id' => $page->id, 'title' => 'Secret link']);

    $this->get('/'.$page->username)->assertOk()->assertDontSee('Secret link');
});

test('scheduled links respect their window', function () {
    $page = Page::factory()->create();
    Link::factory()->scheduled(now()->addDay())->create(['page_id' => $page->id, 'title' => 'Future link']);
    Link::factory()->scheduled(now()->subDay(), now()->addDay())->create(['page_id' => $page->id, 'title' => 'Current link']);
    Link::factory()->scheduled(now()->subDays(2), now()->subDay())->create(['page_id' => $page->id, 'title' => 'Expired link']);

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertDontSee('Future link')
        ->assertSee('Current link')
        ->assertDontSee('Expired link');
});

test('links are rendered in position order', function () {
    $page = Page::factory()->create();
    Link::factory()->create(['page_id' => $page->id, 'title' => 'Second link', 'position' => 1]);
    Link::factory()->create(['page_id' => $page->id, 'title' => 'First link', 'position' => 0]);

    $this->get('/'.$page->username)->assertOk()->assertSeeInOrder(['First link', 'Second link']);
});

test('highlighted links get the highlighted treatment', function () {
    $page = Page::factory()->create();
    Link::factory()->highlighted()->create(['page_id' => $page->id]);

    $this->get('/'.$page->username)->assertOk()->assertSee('data-highlighted', false);
});

test('the page includes SEO and Open Graph tags', function () {
    $page = Page::factory()->create(['username' => 'ada', 'bio' => 'Math and code.']);

    $response = $this->get('/ada');

    $response->assertOk()
        ->assertSee('<title>@ada', false)
        ->assertSee('<meta name="description" content="Math and code.">', false)
        ->assertSee('<meta property="og:title"', false)
        ->assertSee('<link rel="canonical"', false);
});

test('the footer shows the configurable attribution', function () {
    config(['nexo.attribution.label' => 'powered by alvarocdev.com', 'nexo.attribution.url' => 'https://alvarocdev.com']);
    $page = Page::factory()->create();

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertSee('powered by alvarocdev.com')
        ->assertSee('https://alvarocdev.com');
});

test('reserved app routes are not shadowed by the catch-all', function () {
    $this->get('/register')->assertOk();
    $this->get('/login')->assertOk();
});

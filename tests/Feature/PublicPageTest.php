<?php

use App\Models\Link;
use App\Models\Page;
use App\Models\SocialLink;

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

test('an upcoming link with countdown shows a teaser instead of a link', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->scheduled(now()->addDays(2))->create([
        'page_id' => $page->id,
        'title' => 'Big launch',
        'show_countdown' => true,
    ]);

    $response = $this->get('/'.$page->username);

    $response->assertOk()
        ->assertSee('Big launch')
        ->assertSee('data-countdown', false)
        ->assertDontSee(route('link.visit', $link));
});

test('an upcoming link without countdown stays hidden', function () {
    $page = Page::factory()->create();
    Link::factory()->scheduled(now()->addDays(2))->create([
        'page_id' => $page->id,
        'title' => 'Silent launch',
        'show_countdown' => false,
    ]);

    $this->get('/'.$page->username)->assertOk()->assertDontSee('Silent launch');
});

test('a custom solid background is applied with readable ink', function () {
    $page = Page::factory()->create([
        'background_type' => 'solid',
        'background_start' => '#111111',
    ]);

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertSee('background: #111111', false)
        ->assertSee('text-neutral-50', false);
});

test('a gradient background renders both colors', function () {
    $page = Page::factory()->create([
        'background_type' => 'gradient',
        'background_start' => '#ffffff',
        'background_end' => '#aabbcc',
    ]);

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertSee('linear-gradient(160deg, #ffffff, #aabbcc)', false);
});

test('the theme accent is used for highlighted links', function () {
    $page = Page::factory()->create(['theme' => 'sunset']);
    Link::factory()->highlighted()->create(['page_id' => $page->id]);

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertSee('#f97316', false);
});

test('avatar and banner images are rendered when set', function () {
    $page = Page::factory()->create([
        'avatar_path' => 'avatars/me.png',
        'banner_path' => 'banners/hero.png',
    ]);

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertSee('/storage/avatars/me.png')
        ->assertSee('/storage/banners/hero.png');
});

test('social icons render in the footer with the right urls', function () {
    $page = Page::factory()->create();
    SocialLink::factory()->create(['page_id' => $page->id, 'platform' => 'instagram', 'value' => 'ada.dev']);
    SocialLink::factory()->create(['page_id' => $page->id, 'platform' => 'whatsapp', 'value' => '+5491122334455', 'position' => 1]);
    SocialLink::factory()->create(['page_id' => $page->id, 'platform' => 'email', 'value' => 'hi@example.com', 'position' => 2]);

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertSee('https://instagram.com/ada.dev')
        ->assertSee('https://wa.me/5491122334455')
        ->assertSee('mailto:hi@example.com')
        ->assertSee('aria-label="Instagram"', false);
});

test('the social footer is omitted when there are no social links', function () {
    $page = Page::factory()->create();

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertDontSee('Social profiles');
});

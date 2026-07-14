<?php

use App\Models\Link;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

test('public pages are served from cache until content changes', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->create(['page_id' => $page->id, 'title' => 'Original title']);

    $this->get('/'.$page->username)->assertOk()->assertSee('Original title');

    // A raw update skips model events: the cached copy keeps serving.
    DB::table('links')->where('id', $link->id)->update(['title' => 'Sneaky title']);
    $this->get('/'.$page->username)->assertOk()
        ->assertSee('Original title')
        ->assertDontSee('Sneaky title');

    // A real update busts the cache immediately.
    $link->refresh()->update(['title' => 'Edited title']);
    $this->get('/'.$page->username)->assertOk()->assertSee('Edited title');
});

test('design changes bust the public page cache', function () {
    $page = Page::factory()->create();

    $this->get('/'.$page->username)->assertOk()->assertDontSee('Fresh new bio');

    $this->actingAs($page->user)->patch('/design', [
        'bio' => 'Fresh new bio',
        'theme' => 'default',
        'background_type' => 'default',
    ]);

    $this->get('/'.$page->username)->assertOk()->assertSee('Fresh new bio');
});

test('reordering busts the public page cache', function () {
    $page = Page::factory()->create();
    $first = Link::factory()->create(['page_id' => $page->id, 'position' => 0, 'title' => 'Alpha link']);
    $second = Link::factory()->create(['page_id' => $page->id, 'position' => 1, 'title' => 'Beta link']);

    $this->get('/'.$page->username)->assertSeeInOrder(['Alpha link', 'Beta link']);

    $this->actingAs($page->user)->patchJson('/links/reorder', [
        'links' => [$second->id, $first->id],
    ])->assertNoContent();

    $this->get('/'.$page->username)->assertSeeInOrder(['Beta link', 'Alpha link']);
});

test('each locale gets its own cached copy', function () {
    $page = Page::factory()->create();

    $this->get('/'.$page->username.'?lang=es')->assertOk()->assertSee('Crea la tuya');
    $this->flushSession();
    $this->get('/'.$page->username.'?lang=en')->assertOk()->assertSee('Create yours');
});

test('the sitemap lists public pages with the right content type', function () {
    $page = Page::factory()->create();

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee(url('/'))
        ->assertSee(route('help'))
        ->assertSee(route('page.show', $page->username));
});

test('robots.txt blocks private routes and links to the sitemap', function () {
    $this->get('/robots.txt')
        ->assertOk()
        ->assertSee('Disallow: /l/')
        ->assertSee('Disallow: /dashboard')
        ->assertSee('Sitemap: '.route('sitemap'));
});

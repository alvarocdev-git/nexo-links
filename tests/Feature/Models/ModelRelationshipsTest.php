<?php

use App\Models\Click;
use App\Models\Link;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\QueryException;

it('associates a user with their page', function () {
    $page = Page::factory()->create();

    expect($page->user)->toBeInstanceOf(User::class)
        ->and($page->user->page->is($page))->toBeTrue();
});

it('returns page links ordered by position', function () {
    $page = Page::factory()->create();
    Link::factory()->create(['page_id' => $page->id, 'position' => 2, 'title' => 'third']);
    Link::factory()->create(['page_id' => $page->id, 'position' => 0, 'title' => 'first']);
    Link::factory()->create(['page_id' => $page->id, 'position' => 1, 'title' => 'second']);

    expect($page->links->pluck('title')->all())->toBe(['first', 'second', 'third']);
});

it('associates clicks with a link', function () {
    $link = Link::factory()->create();
    Click::factory()->count(3)->create(['link_id' => $link->id]);

    expect($link->clicks)->toHaveCount(3)
        ->and($link->clicks->first()->link->is($link))->toBeTrue();
});

it('deletes pages, links and clicks when the user is deleted', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->create(['page_id' => $page->id]);
    Click::factory()->create(['link_id' => $link->id]);

    $page->user->delete();

    expect(Page::count())->toBe(0)
        ->and(Link::count())->toBe(0)
        ->and(Click::count())->toBe(0);
});

it('rejects a duplicate username', function () {
    Page::factory()->create(['username' => 'taken']);

    Page::factory()->create(['username' => 'taken']);
})->throws(QueryException::class);

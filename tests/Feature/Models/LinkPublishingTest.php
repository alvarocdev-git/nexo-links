<?php

use App\Models\Link;

it('publishes a visible link with no schedule', function () {
    $link = Link::factory()->create();

    expect($link->isPublished())->toBeTrue();
});

it('does not publish a hidden link', function () {
    $link = Link::factory()->hidden()->create();

    expect($link->isPublished())->toBeFalse();
});

it('does not publish a link before its start date', function () {
    $link = Link::factory()->scheduled(now()->addDay())->create();

    expect($link->isPublished())->toBeFalse();
});

it('publishes a link inside its schedule window', function () {
    $link = Link::factory()->scheduled(now()->subDay(), now()->addDay())->create();

    expect($link->isPublished())->toBeTrue();
});

it('does not publish a link after its end date', function () {
    $link = Link::factory()->scheduled(now()->subDays(2), now()->subDay())->create();

    expect($link->isPublished())->toBeFalse();
});

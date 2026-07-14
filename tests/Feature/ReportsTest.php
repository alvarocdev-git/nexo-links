<?php

use App\Models\Link;
use App\Models\Page;
use App\Models\Report;

test('the report form renders from the public page footer', function () {
    $page = Page::factory()->create();

    $this->get('/'.$page->username)
        ->assertOk()
        ->assertSee(route('report.create', $page->username));

    $this->get(route('report.create', $page->username))
        ->assertOk()
        ->assertSee('Report this page');
});

test('a visitor can report a whole page anonymously', function () {
    $page = Page::factory()->create();

    $response = $this->post(route('report.store', $page->username), [
        'reason' => 'malicious',
        'details' => 'This page pretends to be a bank.',
    ]);

    $response->assertRedirect(route('report.create', $page->username));

    $report = Report::sole();
    expect($report->page_id)->toBe($page->id)
        ->and($report->link_id)->toBeNull()
        ->and($report->status)->toBe('open')
        ->and(strlen($report->visitor_hash))->toBe(64);
});

test('a visitor can report a specific link', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->create(['page_id' => $page->id]);

    $this->post(route('report.store', $page->username), [
        'reason' => 'broken',
        'link_id' => $link->id,
    ]);

    expect(Report::sole()->link_id)->toBe($link->id);
});

test('a link from another page cannot be referenced', function () {
    $page = Page::factory()->create();
    $foreignLink = Link::factory()->create();

    $this->post(route('report.store', $page->username), [
        'reason' => 'broken',
        'link_id' => $foreignLink->id,
    ])->assertSessionHasErrors('link_id');

    expect(Report::count())->toBe(0);
});

test('an invalid reason is rejected', function () {
    $page = Page::factory()->create();

    $this->post(route('report.store', $page->username), [
        'reason' => 'i-dont-like-it',
    ])->assertSessionHasErrors('reason');
});

test('the same visitor cannot report the same page twice in a day', function () {
    $page = Page::factory()->create();

    $this->post(route('report.store', $page->username), ['reason' => 'spam']);
    $this->post(route('report.store', $page->username), ['reason' => 'spam'])
        ->assertSessionHasErrors('reason');

    expect(Report::count())->toBe(1);
});

test('the dashboard shows a banner when there are open reports', function () {
    $page = Page::factory()->create();
    Report::factory()->count(2)->create(['page_id' => $page->id]);
    Report::factory()->resolved()->create(['page_id' => $page->id]);

    $this->actingAs($page->user)->get('/dashboard')
        ->assertOk()
        ->assertSee('You have 2 open reports.')
        ->assertSee(route('reports.index'));
});

test('the dashboard shows no banner without open reports', function () {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->get('/dashboard')
        ->assertOk()
        ->assertDontSee('open report');
});

test('the owner sees their reports with reason and target', function () {
    $page = Page::factory()->create();
    $link = Link::factory()->create(['page_id' => $page->id, 'title' => 'Suspicious link']);
    Report::factory()->create(['page_id' => $page->id, 'link_id' => $link->id, 'reason' => 'broken']);
    Report::factory()->create(['reason' => 'spam']);

    $this->actingAs($page->user)->get('/reports')
        ->assertOk()
        ->assertSee('Broken link')
        ->assertSee('Suspicious link')
        ->assertDontSee('Spam');
});

test('the owner can resolve a report but not someone else\'s', function () {
    $page = Page::factory()->create();
    $own = Report::factory()->create(['page_id' => $page->id]);
    $foreign = Report::factory()->create();

    $this->actingAs($page->user)->patch("/reports/{$foreign->id}")->assertForbidden();

    $this->actingAs($page->user)->patch("/reports/{$own->id}")
        ->assertRedirect('/reports');

    expect($own->refresh()->status)->toBe('resolved')
        ->and($foreign->refresh()->status)->toBe('open');
});

test('guests cannot see the reports panel', function () {
    $this->get('/reports')->assertRedirect('/login');
});

test('report and reports are reserved usernames', function () {
    $this->post('/register', [
        'name' => 'Test',
        'username' => 'report',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors('username');
});

test('the report form is translated', function () {
    $page = Page::factory()->create();

    $this->get(route('report.create', ['page' => $page->username, 'lang' => 'es']))
        ->assertOk()
        ->assertSee('Reportar esta página')
        ->assertSee('Link roto');
});

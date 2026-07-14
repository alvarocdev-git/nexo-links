<?php

use App\Models\Page;
use App\Models\SocialLink;

test('guests cannot add social links', function () {
    $this->post('/socials', ['platform' => 'instagram', 'value' => 'someone'])
        ->assertRedirect('/login');
});

test('a user can add a social link', function () {
    $page = Page::factory()->create();

    $response = $this->actingAs($page->user)->post('/socials', [
        'platform' => 'instagram',
        'value' => 'alvarocdev',
    ]);

    $response->assertRedirect('/dashboard');
    expect($page->socialLinks()->sole())
        ->platform->toBe('instagram')
        ->value->toBe('alvarocdev');
});

test('adding the same platform twice is rejected', function () {
    $page = Page::factory()->create();
    SocialLink::factory()->create(['page_id' => $page->id, 'platform' => 'instagram']);

    $this->actingAs($page->user)->post('/socials', [
        'platform' => 'instagram',
        'value' => 'another',
    ])->assertSessionHasErrors('platform');
});

test('the same platform on different pages is allowed', function () {
    SocialLink::factory()->create(['platform' => 'instagram']);
    $page = Page::factory()->create();

    $this->actingAs($page->user)->post('/socials', [
        'platform' => 'instagram',
        'value' => 'mine',
    ])->assertSessionHasNoErrors();
});

test('values are validated per platform type', function (string $platform, string $value) {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->post('/socials', [
        'platform' => $platform,
        'value' => $value,
    ])->assertSessionHasErrors('value');
})->with([
    ['instagram', 'with spaces'],
    ['instagram', '@withat'],
    ['email', 'not-an-email'],
    ['whatsapp', '12345'],
    ['whatsapp', 'phone-words'],
    ['phone', '1122334455'],
    ['website', 'javascript:alert(1)'],
]);

test('valid values per platform type are accepted', function (string $platform, string $value) {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->post('/socials', [
        'platform' => $platform,
        'value' => $value,
    ])->assertSessionHasNoErrors();
})->with([
    ['tiktok', 'my.handle_ok'],
    ['email', 'hi@example.com'],
    ['whatsapp', '+5491122334455'],
    ['website', 'https://alvarocdev.com'],
]);

test('an unknown platform is rejected', function () {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->post('/socials', [
        'platform' => 'myspace',
        'value' => 'whatever',
    ])->assertSessionHasErrors('platform');
});

test('a user can remove their own social link but not others', function () {
    $page = Page::factory()->create();
    $own = SocialLink::factory()->create(['page_id' => $page->id]);
    $foreign = SocialLink::factory()->create();

    $this->actingAs($page->user)->delete("/socials/{$foreign->id}")->assertForbidden();
    $this->actingAs($page->user)->delete("/socials/{$own->id}")->assertRedirect('/dashboard');

    expect(SocialLink::count())->toBe(1);
});

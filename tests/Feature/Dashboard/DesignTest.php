<?php

use App\Models\Page;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// A real 1x1 PNG so the `image` validation rule passes without the GD extension
// (UploadedFile::fake()->image() needs GD, which the container test runner lacks).
function fakeImageUpload(string $name): UploadedFile
{
    $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

    return UploadedFile::fake()->createWithContent($name, $png);
}

test('guests cannot open the design page', function () {
    $this->get('/design')->assertRedirect('/login');
});

test('the design page shows the current settings', function () {
    $page = Page::factory()->create(['bio' => 'My current bio']);

    $this->actingAs($page->user)->get('/design')
        ->assertOk()
        ->assertSee('My current bio');
});

test('a user can update bio, theme and a solid background', function () {
    $page = Page::factory()->create();

    $response = $this->actingAs($page->user)->patch('/design', [
        'bio' => 'New bio',
        'theme' => 'ocean',
        'background_type' => 'solid',
        'background_start' => '#112233',
    ]);

    $response->assertRedirect('/design');
    expect($page->refresh())
        ->bio->toBe('New bio')
        ->theme->toBe('ocean')
        ->background_type->toBe('solid')
        ->background_start->toBe('#112233');
});

test('a gradient background requires both colors', function () {
    $page = Page::factory()->create();

    $response = $this->actingAs($page->user)->patch('/design', [
        'theme' => 'default',
        'background_type' => 'gradient',
        'background_start' => '#112233',
    ]);

    $response->assertSessionHasErrors('background_end');
});

test('invalid colors and themes are rejected', function () {
    $page = Page::factory()->create();

    $this->actingAs($page->user)->patch('/design', [
        'theme' => 'default',
        'background_type' => 'solid',
        'background_start' => 'red',
    ])->assertSessionHasErrors('background_start');

    $this->actingAs($page->user)->patch('/design', [
        'theme' => 'not-a-theme',
        'background_type' => 'default',
    ])->assertSessionHasErrors('theme');
});

test('a user can upload and replace an avatar', function () {
    Storage::fake('public');
    $page = Page::factory()->create();

    $this->actingAs($page->user)->patch('/design', [
        'theme' => 'default',
        'background_type' => 'default',
        'avatar' => fakeImageUpload('me.png'),
    ]);

    $firstPath = $page->refresh()->avatar_path;
    Storage::disk('public')->assertExists($firstPath);

    $this->actingAs($page->user)->patch('/design', [
        'theme' => 'default',
        'background_type' => 'default',
        'avatar' => fakeImageUpload('new.png'),
    ]);

    Storage::disk('public')->assertMissing($firstPath);
    Storage::disk('public')->assertExists($page->refresh()->avatar_path);
});

test('a user can remove their banner', function () {
    Storage::fake('public');
    $page = Page::factory()->create();

    $this->actingAs($page->user)->patch('/design', [
        'theme' => 'default',
        'background_type' => 'default',
        'banner' => fakeImageUpload('banner.png'),
    ]);

    $path = $page->refresh()->banner_path;
    expect($path)->not->toBeNull();

    $this->actingAs($page->user)->patch('/design', [
        'theme' => 'default',
        'background_type' => 'default',
        'remove_banner' => 1,
    ]);

    expect($page->refresh()->banner_path)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

test('non-image uploads are rejected', function () {
    Storage::fake('public');
    $page = Page::factory()->create();

    $this->actingAs($page->user)->patch('/design', [
        'theme' => 'default',
        'background_type' => 'default',
        'avatar' => UploadedFile::fake()->create('script.php', 10),
    ])->assertSessionHasErrors('avatar');
});

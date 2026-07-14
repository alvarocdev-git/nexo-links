<?php

namespace Database\Seeders;

use App\Models\Click;
use App\Models\Link;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with a demo account.
     *
     * Login: demo@nexo.test / password
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@nexo.test',
        ]);

        $page = Page::factory()->for($user)->create([
            'username' => 'demo',
            'bio' => 'Building Nexo — open-source link-in-bio.',
        ]);

        $links = collect([
            Link::factory()->highlighted()->create([
                'page_id' => $page->id,
                'title' => 'Live now — building in public',
                'url' => 'https://twitch.tv/demo',
                'position' => 0,
            ]),
            Link::factory()->create([
                'page_id' => $page->id,
                'title' => 'My portfolio',
                'url' => 'https://alvarocdev.com',
                'position' => 1,
            ]),
            Link::factory()->scheduled(now()->addDays(3))->create([
                'page_id' => $page->id,
                'title' => 'Launch event',
                'url' => 'https://example.com/launch',
                'position' => 2,
                'show_countdown' => true,
            ]),
            Link::factory()->hidden()->create([
                'page_id' => $page->id,
                'title' => 'Old blog (hidden)',
                'url' => 'https://example.com/blog',
                'position' => 3,
            ]),
        ]);

        $links->each(function (Link $link) {
            Click::factory()->count(random_int(5, 40))->create(['link_id' => $link->id]);
        });
    }
}

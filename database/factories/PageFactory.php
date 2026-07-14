<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'username' => Str::of(fake()->unique()->userName())
                ->lower()
                ->replaceMatches('/[^a-z0-9_-]/', '')
                ->limit(30, '')
                ->toString(),
            'bio' => fake()->sentence(8),
            'avatar_path' => null,
            'theme' => 'default',
        ];
    }
}

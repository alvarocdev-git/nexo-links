<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SocialLink>
 */
class SocialLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::factory(),
            'platform' => 'instagram',
            'value' => fake()->userName(),
            'position' => 0,
        ];
    }
}

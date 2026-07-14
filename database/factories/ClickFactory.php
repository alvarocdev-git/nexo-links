<?php

namespace Database\Factories;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Click>
 */
class ClickFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'link_id' => Link::factory(),
            'visitor_hash' => hash('sha256', Str::random(40)),
            'referrer_host' => fake()->randomElement([null, 'google.com', 'x.com', 'instagram.com']),
            'created_at' => fake()->dateTimeBetween('-30 days'),
        ];
    }
}

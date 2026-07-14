<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Link>
 */
class LinkFactory extends Factory
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
            'title' => fake()->words(3, true),
            'url' => fake()->url(),
            'position' => 0,
            'is_visible' => true,
            'is_highlighted' => false,
            'show_countdown' => false,
            'starts_at' => null,
            'ends_at' => null,
        ];
    }

    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => ['is_visible' => false]);
    }

    public function highlighted(): static
    {
        return $this->state(fn (array $attributes) => ['is_highlighted' => true]);
    }

    public function scheduled(\DateTimeInterface $from, ?\DateTimeInterface $until = null): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => $from,
            'ends_at' => $until,
        ]);
    }
}

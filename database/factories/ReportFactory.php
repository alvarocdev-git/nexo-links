<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
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
            'link_id' => null,
            'reason' => fake()->randomElement(array_keys(config('nexo.report_reasons'))),
            'details' => fake()->boolean(40) ? fake()->sentence() : null,
            'status' => 'open',
            'visitor_hash' => hash('sha256', Str::random(40)),
        ];
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'resolved']);
    }
}

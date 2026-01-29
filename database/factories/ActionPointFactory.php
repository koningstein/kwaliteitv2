<?php

namespace Database\Factories;

use App\Models\ActionPointStatus;
use App\Models\Criterion;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActionPoint>
 */
class ActionPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'criterion_id' => fn () => \App\Models\Criterion::inRandomOrder()->first()?->id ?? \App\Models\Criterion::factory(),
            'user_id' => fn () => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'team_id' => fn () => \App\Models\Team::inRandomOrder()->first()?->id ?? \App\Models\Team::factory(),
            'action_point_status_id' => fn () => \App\Models\ActionPointStatus::inRandomOrder()->first()?->id,
            'description' => $this->faker->sentence(),
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ];
    }
}

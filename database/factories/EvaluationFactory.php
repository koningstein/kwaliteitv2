<?php

namespace Database\Factories;

use App\Models\ActionPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluation>
 */
class EvaluationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'action_point_id' => fn () => ActionPoint::inRandomOrder()->first()?->id,
            'description' => $this->faker->paragraph(),
            'created_at' => now()->subDays(rand(1, 30)),
        ];
    }
}

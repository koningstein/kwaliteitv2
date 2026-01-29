<?php

namespace Database\Factories;

use App\Models\Standard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Criterion>
 */
class CriterionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'standard_id' => Standard::inRandomOrder()->first()->id,
            'number' => $this->faker->numberBetween(1, 20),
            'text' => $this->faker->paragraph(),
            'explanation' => $this->faker->optional()->sentence(),
        ];
    }
}

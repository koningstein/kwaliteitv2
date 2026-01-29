<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportingPeriod>
 */
class ReportingPeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = $this->faker->unique()->numberBetween(2024, 2030);

        return [
            'slug' => (string) $year,
            'label' => 'Jaar '.$year,
            'is_active' => false,
            'sort_order' => 0,
        ];
    }
}

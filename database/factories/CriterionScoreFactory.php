<?php

namespace Database\Factories;

use App\Models\Criterion;
use App\Models\ReportingPeriod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CriterionScore>
 */
class CriterionScoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'criterion_id' => Criterion::inRandomOrder()->first()->id,
            'reporting_period_id' => ReportingPeriod::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement(['sufficient', 'attention', 'insufficient']),
            'updated_by' => User::inRandomOrder()->first()->id,
        ];
    }
}

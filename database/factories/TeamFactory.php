<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Software Development',
                'System & Network Engineering',
                'Medewerker ICT',
                'Laboratorium Engineer',
                'Data Science & AI',
                'Cybersecurity',
                'Cloud Engineering',
                'Business IT & Management',
                'Smart Technology',
                'Game Development',
            ]),
        ];
    }
}

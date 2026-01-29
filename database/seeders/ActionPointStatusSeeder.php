<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActionPointStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Niet gestart',
            'Op schema',
            'Loopt achter',
            'Uitgesteld',
            'Afgerond',
        ];

        foreach ($statuses as $status) {
            \App\Models\ActionPointStatus::create(['name' => $status]);
        }
    }
}

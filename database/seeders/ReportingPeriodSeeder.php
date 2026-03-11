<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReportingPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ReportingPeriod::create([
            'slug' => '2025',
            'label' => '2025',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        \App\Models\ReportingPeriod::create([
            'slug' => '2026',
            'label' => '2026',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        \App\Models\ReportingPeriod::create([
            'slug' => '2027',
            'label' => '2027',
            'is_active' => true,
            'sort_order' => 3,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Criterion;
use App\Models\CriterionScore;
use App\Models\Indicator;
use App\Models\ReportingPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriterionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periods = ReportingPeriod::all();

        Criterion::all()->each(function ($criterion) use ($periods) {
            // Voeg 4 indicatoren (vinklijstjes) toe per criterium
            Indicator::factory(4)->create([
                'criterion_id' => $criterion->id,
            ]);

            // Geef elk criterium een score voor elk aangemaakt jaar
            foreach ($periods as $period) {
                CriterionScore::factory()->create([
                    'criterion_id' => $criterion->id,
                    'reporting_period_id' => $period->id,
                ]);
            }
        });
    }
}

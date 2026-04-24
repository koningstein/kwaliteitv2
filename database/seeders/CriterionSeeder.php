<?php

namespace Database\Seeders;

use App\Models\Criterion;
use App\Models\CriterionScore;
use App\Models\ReportingPeriod;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class CriterionSeeder extends Seeder
{
    public function run(): void
    {
        $period2025 = ReportingPeriod::where('slug', '2025')->firstOrFail();
        $period2026 = ReportingPeriod::where('slug', '2026')->firstOrFail();
        $period2027 = ReportingPeriod::where('slug', '2027')->firstOrFail();

        $teams = Team::all();
        $criteria = Criterion::where('reporting_period_id', $period2025->id)->get();

        $statuses = ['sufficient', 'attention', 'insufficient'];

        foreach ($teams as $team) {
            // Kwaliteitsmedewerker van dit team
            $kwaliteitUser = User::whereHas('roles', fn($q) => $q->where('name', 'kwaliteitszorg'))
                ->whereHas('teams', fn($q) => $q->where('teams.id', $team->id))
                ->first();

            if (!$kwaliteitUser) {
                continue;
            }

            // Alle teams krijgen scores voor 2025
            foreach ($criteria as $criterion) {
                CriterionScore::create([
                    'criterion_id'        => $criterion->id,
                    'reporting_period_id' => $period2025->id,
                    'team_id'             => $team->id,
                    'status'              => $statuses[array_rand($statuses)],
                    'updated_by'          => $kwaliteitUser->id,
                ]);
            }

            // Alleen Data & AI krijgt ook scores voor 2026 en 2027
            if ($team->name === 'Data & AI') {
                foreach ($criteria as $criterion) {
                    CriterionScore::create([
                        'criterion_id'        => $criterion->id,
                        'reporting_period_id' => $period2026->id,
                        'team_id'             => $team->id,
                        'status'              => $statuses[array_rand($statuses)],
                        'updated_by'          => $kwaliteitUser->id,
                    ]);

                    CriterionScore::create([
                        'criterion_id'        => $criterion->id,
                        'reporting_period_id' => $period2027->id,
                        'team_id'             => $team->id,
                        'status'              => $statuses[array_rand($statuses)],
                        'updated_by'          => $kwaliteitUser->id,
                    ]);
                }
            }
        }
    }
}

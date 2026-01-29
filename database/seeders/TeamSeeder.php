<?php

namespace Database\Seeders;

use App\Models\ActionPoint;
use App\Models\Evaluation;
use App\Models\Location;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $locations = Location::all();
        $teams = Team::factory(8)->create();

        // Koppel 1-3 locaties per team
        foreach ($teams as $team) {
            $team->locations()->attach(
                $locations->random(rand(1, 3))->pluck('id')
            );
        }

        // Maak 40 docenten aan met locaties
        $docenten = User::factory(40)->create();
        foreach ($docenten as $docent) {
            $docent->locations()->attach(
                $locations->random(rand(1, 2))->pluck('id')
            );
        }

        // Maak 6 teamleiders aan met locaties
        $leaderNames = [
            'Jan de Vries', 'Maria Jansen', 'Peter Bakker',
            'Sanne van Dijk', 'Kees Meijer', 'Linda Visser',
        ];

        $leaders = collect();
        foreach ($leaderNames as $name) {
            $leader = User::factory()->create(['name' => $name]);
            $leader->locations()->attach(
                $locations->random(rand(1, 3))->pluck('id')
            );
            $leaders->push($leader);
        }

        // Koppel teamleiders aan teams (elke leider beheert 2-3 teams)
        foreach ($leaders as $leader) {
            $leader->managedTeams()->attach(
                $teams->random(rand(2, 3))->pluck('id')
            );
        }

        // Verdeel docenten over teams (elke docent in 1-2 teams)
        foreach ($docenten as $docent) {
            $docent->teams()->attach(
                $teams->random(rand(1, 2))->pluck('id')
            );

            // Maak 1-2 actiepunten per docent
            $docentTeams = $docent->teams;
            foreach ($docentTeams as $team) {
                $actionPoints = ActionPoint::factory(rand(1, 2))->create([
                    'user_id' => $docent->id,
                    'team_id' => $team->id,
                ]);

                foreach ($actionPoints as $ap) {
                    Evaluation::factory(rand(1, 2))->create([
                        'action_point_id' => $ap->id,
                    ]);
                }
            }
        }
    }
}

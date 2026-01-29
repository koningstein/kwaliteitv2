<?php

namespace Database\Seeders;

use App\Models\ActionPoint;
use App\Models\Evaluation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Maak 3 teams
        $teams = Team::factory(3)->create();

        // Maak een teamleider en koppel deze aan de eerste 2 teams
        $leader = User::factory()->create(['name' => 'Teamleider ICT']);
        $leader->managedTeams()->attach($teams->take(2)->pluck('id'));

        // Maak docenten per team en geef ze actiepunten
        foreach ($teams as $team) {
            $docenten = User::factory(3)->create();

            foreach ($docenten as $docent) {
                $docent->teams()->attach($team->id);

                // Maak 2 actiepunten per docent voor dit team
                $actionPoints = ActionPoint::factory(2)->create([
                    'user_id' => $docent->id,
                    'team_id' => $team->id,
                ]);

                // Voeg aan elk actiepunt 1 of 2 evaluaties toe
                foreach ($actionPoints as $ap) {
                    Evaluation::factory(rand(1, 2))->create([
                        'action_point_id' => $ap->id,
                    ]);
                }
            }
        }
    }
}

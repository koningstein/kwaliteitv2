<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('Welkom123');
        $locations = Location::all();
        $defaultLocation = $locations->first();

        // ─────────────────────────────────────────────
        // DIRECTIE
        // ─────────────────────────────────────────────
        $dirk = User::create([
            'name'              => 'Dirk Janssen',
            'email'             => 'dirk@test.nl',
            'password'          => $password,
            'email_verified_at' => now(),
        ]);
        $dirk->assignRole('directie');
        $dirk->locations()->attach($defaultLocation->id);

        // ─────────────────────────────────────────────
        // O&K MEDEWERKERS
        // ─────────────────────────────────────────────
        $omar = User::create([
            'name'              => 'Omar Khalid',
            'email'             => 'omar@test.nl',
            'password'          => $password,
            'email_verified_at' => now(),
        ]);
        $omar->assignRole('ok_medewerker');
        $omar->locations()->attach($defaultLocation->id);

        $kim = User::create([
            'name'              => 'Kim de Boer',
            'email'             => 'kim@test.nl',
            'password'          => $password,
            'email_verified_at' => now(),
        ]);
        $kim->assignRole('ok_medewerker');
        $kim->locations()->attach($defaultLocation->id);

        // ─────────────────────────────────────────────
        // TEAMS AANMAKEN
        // ─────────────────────────────────────────────
        $teamSD  = Team::create(['name' => 'Software Development']);
        $teamICT = Team::create(['name' => 'ICT Support']);
        $teamSE  = Team::create(['name' => 'System Engineer']);
        $teamNB  = Team::create(['name' => 'Netwerk Beheer']);
        $teamDA  = Team::create(['name' => 'Data & AI']);

        // Koppel locaties aan teams
        $teamSD->locations()->attach($defaultLocation->id);
        $teamICT->locations()->attach($defaultLocation->id);
        $teamSE->locations()->attach($defaultLocation->id);
        $teamNB->locations()->attach($defaultLocation->id);
        $teamDA->locations()->attach($defaultLocation->id);

        // ─────────────────────────────────────────────
        // ONDERWIJSLEIDER 1 — IRIS EIJPE
        // Teams: Software Development, ICT Support, System Engineer
        // ─────────────────────────────────────────────
        $iris = User::create([
            'name'              => 'Iris Eijpe',
            'email'             => 'iris@test.nl',
            'password'          => $password,
            'email_verified_at' => now(),
        ]);
        $iris->assignRole('onderwijsleider');
        $iris->locations()->attach($defaultLocation->id);
        $iris->managedTeams()->attach([$teamSD->id, $teamICT->id, $teamSE->id]);

        // ─────────────────────────────────────────────
        // ONDERWIJSLEIDER 2 — MARK VISSER
        // Teams: Netwerk Beheer, Data & AI
        // ─────────────────────────────────────────────
        $mark = User::create([
            'name'              => 'Mark Visser',
            'email'             => 'mark@test.nl',
            'password'          => $password,
            'email_verified_at' => now(),
        ]);
        $mark->assignRole('onderwijsleider');
        $mark->locations()->attach($defaultLocation->id);
        $mark->managedTeams()->attach([$teamNB->id, $teamDA->id]);

        // ─────────────────────────────────────────────
        // KWALITEITSZORGMEDEWERKERS (1 per team)
        // ─────────────────────────────────────────────
        $kwaliteitUsers = [
            ['name' => 'Sara Devos',   'email' => 'kwaliteit.sd@test.nl',  'team' => $teamSD],
            ['name' => 'Kevin Prins',  'email' => 'kwaliteit.ict@test.nl', 'team' => $teamICT],
            ['name' => 'Lotte Bakker', 'email' => 'kwaliteit.se@test.nl',  'team' => $teamSE],
            ['name' => 'Bram Smit',    'email' => 'kwaliteit.nb@test.nl',  'team' => $teamNB],
            ['name' => 'Noor Aalbers', 'email' => 'kwaliteit.da@test.nl',  'team' => $teamDA],
        ];

        foreach ($kwaliteitUsers as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => $password,
                'email_verified_at' => now(),
            ]);
            $user->assignRole('kwaliteitszorg');
            $user->locations()->attach($defaultLocation->id);
            $user->teams()->attach($data['team']->id);
        }

        // ─────────────────────────────────────────────
        // DOCENTEN / MEDEWERKERS (4 per team)
        // ─────────────────────────────────────────────
        $docentGroups = [
            'sd' => [
                $teamSD,
                [
                    ['name' => 'Tom Berg',      'email' => 'docent1.sd@test.nl'],
                    ['name' => 'Anna Lam',       'email' => 'docent2.sd@test.nl'],
                    ['name' => 'Jesse Vos',      'email' => 'docent3.sd@test.nl'],
                    ['name' => 'Roos Hendriks',  'email' => 'docent4.sd@test.nl'],
                ],
            ],
            'ict' => [
                $teamICT,
                [
                    ['name' => 'Bas Kuiper',     'email' => 'docent1.ict@test.nl'],
                    ['name' => 'Eline Molenaar', 'email' => 'docent2.ict@test.nl'],
                    ['name' => 'Daan Hoek',      'email' => 'docent3.ict@test.nl'],
                    ['name' => 'Fleur Timmerman','email' => 'docent4.ict@test.nl'],
                ],
            ],
            'se' => [
                $teamSE,
                [
                    ['name' => 'Niels Groot',    'email' => 'docent1.se@test.nl'],
                    ['name' => 'Vera Dijkstra',  'email' => 'docent2.se@test.nl'],
                    ['name' => 'Sander Wolf',    'email' => 'docent3.se@test.nl'],
                    ['name' => 'Maya Peters',    'email' => 'docent4.se@test.nl'],
                ],
            ],
            'nb' => [
                $teamNB,
                [
                    ['name' => 'Lars van Dam',   'email' => 'docent1.nb@test.nl'],
                    ['name' => 'Sofie Brouwer',  'email' => 'docent2.nb@test.nl'],
                    ['name' => 'Tim Scholten',   'email' => 'docent3.nb@test.nl'],
                    ['name' => 'Isa Martens',    'email' => 'docent4.nb@test.nl'],
                ],
            ],
            'da' => [
                $teamDA,
                [
                    ['name' => 'Koen Vermeer',   'email' => 'docent1.da@test.nl'],
                    ['name' => 'Lisa Bosman',    'email' => 'docent2.da@test.nl'],
                    ['name' => 'Ruben Steenbeek','email' => 'docent3.da@test.nl'],
                    ['name' => 'Hana Oosterbeek','email' => 'docent4.da@test.nl'],
                ],
            ],
        ];

        foreach ($docentGroups as [$team, $docenten]) {
            foreach ($docenten as $data) {
                $user = User::create([
                    'name'              => $data['name'],
                    'email'             => $data['email'],
                    'password'          => $password,
                    'email_verified_at' => now(),
                ]);
                $user->assignRole('medewerker');
                $user->locations()->attach($defaultLocation->id);
                $user->teams()->attach($team->id);
            }
        }
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Model::unguard();

        // 1. Stamgegevens (Jaren en Statussen)
        $this->call([
            ReportingPeriodSeeder::class,
            ActionPointStatusSeeder::class,
        ]);

        // 2. Kwaliteitsstructuur (Thema -> Standaard -> Criterium)
        // Dit MOET voor de ActionPoints gebeuren
        $this->call([
            ThemeSeeder::class,
            StandardSeeder::class,
        ]);

        // 3. Locaties, Teams en Gebruikers
        $this->call(LocationSeeder::class);
        $this->call(TeamSeeder::class);

        // 4. Scores (nu er Criteria en Users zijn)
        $this->call(CriterionSeeder::class);

        Model::reguard();
    }
}

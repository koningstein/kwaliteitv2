<?php

namespace Database\Seeders;

use App\Models\Standard;
use App\Models\Theme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // De specifieke thema's uit het prototype
        $themes = [
            ['code' => 'OP', 'name' => 'Onderwijsproces', 'color' => '#2563eb'],
            ['code' => 'VS', 'name' => 'Veiligheid en schoolklimaat', 'color' => '#16a34a'],
            ['code' => 'BA', 'name' => 'Borging en afsluiting', 'color' => '#dc2626'],
            ['code' => 'OR', 'name' => 'Onderwijsresultaten', 'color' => '#ca8a04'],
            ['code' => 'SKA', 'name' => 'Sturen, kwaliteitszorg en ambitie', 'color' => '#7c3aed'],
        ];

        foreach ($themes as $themeData) {
            $theme = Theme::create($themeData);

            // Maak voor elk thema minimaal 2 standaarden aan via de factory
            Standard::factory(2)->create([
                'theme_id' => $theme->id,
            ]);
        }
    }
}

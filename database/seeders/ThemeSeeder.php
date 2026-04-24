<?php

namespace Database\Seeders;

use App\Models\ReportingPeriod;
use App\Models\Standard;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        // De specifieke thema's uit het prototype
        $themes = [
            ['code' => 'OP',  'name' => 'Onderwijsproces',                    'color' => '#2563eb'],
            ['code' => 'VS',  'name' => 'Veiligheid en schoolklimaat',         'color' => '#16a34a'],
            ['code' => 'BA',  'name' => 'Borging en afsluiting',               'color' => '#dc2626'],
            ['code' => 'OR',  'name' => 'Onderwijsresultaten',                 'color' => '#ca8a04'],
            ['code' => 'SKA', 'name' => 'Sturen, kwaliteitszorg en ambitie',   'color' => '#7c3aed'],
        ];

        foreach ($themes as $themeData) {
            Theme::create($themeData);
        }
    }
}

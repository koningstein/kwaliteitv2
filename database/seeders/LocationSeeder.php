<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Schiedamseweg 245', 'abbreviation' => 'SHW'],
            ['name' => 'Lloydstraat 300', 'abbreviation' => 'LLY'],
            ['name' => 'Erasmuspad 10', 'abbreviation' => 'ERA'],
            ['name' => 'Marconistraat 16', 'abbreviation' => 'MAR'],
            ['name' => 'Beukelsdijk 90', 'abbreviation' => 'BEU'],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}

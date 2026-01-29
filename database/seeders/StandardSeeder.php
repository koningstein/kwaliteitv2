<?php

namespace Database\Seeders;

use App\Models\Criterion;
use App\Models\Standard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Standard::all()->each(function ($standard) {
            // Maak 3 tot 5 criteria per standaard
            Criterion::factory(rand(3, 5))->create([
                'standard_id' => $standard->id,
            ]);
        });
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('branches')->insert([
        ['nom' => 'Succursale 1',
        'nb_civic' => 123,
        'rue' => 'Rue de la Paix',
        'ville' => 'Sherbrooke',],
        ['nom' => 'Succursale 2',
        'nb_civic' => 223,
        'rue' => 'Rue de la Paix',
        'ville' => 'Sherbrooke',]
    ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('branches')->insert([
        ['name' => 'Succursale 1',
        'civic_no' => 123,
        'road' => 'Rue de la Paix',
        'city' => 'Sherbrooke',],
        ['name' => 'Succursale 2',
        'civic_no' => 223,
        'road' => 'Rue de la Paix',
        'city' => 'Sherbrooke',]
    ]);

    }
}

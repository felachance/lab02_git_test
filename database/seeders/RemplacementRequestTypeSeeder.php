<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemplacementRequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('replacement_request_types')->insert([
            [
                'name' => 'En attente', //1
                'description' => 'Demande en attente',
            ],
            [
                'name' => 'Acceptée', //2
                'description' => 'Demande acceptée',
            ],
            [
                'name' => 'Expirée', //3
                'description' => 'Demande expirée',
            ],
            [
                'name' => 'Annulée', //4
                'description' => 'Demande annulée',
            ]
        ]);
    }
}

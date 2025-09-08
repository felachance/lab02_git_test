<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestAssignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('assignment')->insert([
            [
                'assigned_at' => now(),
                'id_shift' => 1,
                'id_user' => 6,
            ],
            [
                'assigned_at' => now(),
                'id_shift' => 2,
                'id_user' => 6,
            ],
            [
                'assigned_at' => now(),
                'id_shift' => 2,
                'id_user' => 2,
            ],
            [
                'assigned_at' => now(),
                'id_shift' => 3,
                'id_user' => 6,
            ],
            [
                'assigned_at' => now(),
                'id_shift' => 4,
                'id_user' => 4,
            ],
            [
                'assigned_at' => now(),
                'id_shift' => 5,
                'id_user' => 5,
            ],
        ]);
    }
}

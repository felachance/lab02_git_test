<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('shifts')->insert([
            [
                'id_branch' => 1,
                'date' => '2025-06-14',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
            ],
            [
                'id_branch' => 1,
                'date' => '2025-05-24',
                'start_time' => '08:30:00',
                'end_time' => '16:00:00',
            ],
            [
                'id_branch' => 1,
                'date' => '2025-04-14',
                'start_time' => '12:30:00',
                'end_time' => '18:00:00',
            ],
            [
                'id_branch' => 1,
                'date' => '2025-04-15',
                'start_time' => '08:30:00',
                'end_time' => '16:00:00',
            ],
            [
                'id_branch' => 2,
                'date' => '2025-04-14',
                'start_time' => '08:30:00',
                'end_time' => '16:00:00',
            ],
            [
                'id_branch' => 2,
                'date' => '2025-04-15',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
            ],
        ]);
    }
}

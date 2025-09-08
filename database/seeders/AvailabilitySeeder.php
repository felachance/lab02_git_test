<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('availabilities')->insert([
            ['id_user' => 1, 'day_of_week' => 1, 'start_time' => '08:00:00', 'end_time' => '12:00:00'],
            ['id_user' => 1, 'day_of_week' => 3, 'start_time' => '14:00:00', 'end_time' => '18:00:00'],
            ['id_user' => 1, 'day_of_week' => 5, 'start_time' => '09:00:00', 'end_time' => '13:00:00'],
        
            ['id_user' => 2, 'day_of_week' => 0, 'start_time' => '10:00:00', 'end_time' => '14:00:00'],
            ['id_user' => 2, 'day_of_week' => 2, 'start_time' => '13:00:00', 'end_time' => '17:00:00'],
            ['id_user' => 2, 'day_of_week' => 4, 'start_time' => '09:00:00', 'end_time' => '12:00:00'],
        
            ['id_user' => 3, 'day_of_week' => 1, 'start_time' => '15:00:00', 'end_time' => '19:00:00'],
            ['id_user' => 3, 'day_of_week' => 3, 'start_time' => '07:00:00', 'end_time' => '11:00:00'],
            ['id_user' => 3, 'day_of_week' => 6, 'start_time' => '12:00:00', 'end_time' => '16:00:00'],
        
            ['id_user' => 4, 'day_of_week' => 2, 'start_time' => '08:00:00', 'end_time' => '12:00:00'],
            ['id_user' => 4, 'day_of_week' => 4, 'start_time' => '14:00:00', 'end_time' => '18:00:00'],
            ['id_user' => 4, 'day_of_week' => 6, 'start_time' => '10:00:00', 'end_time' => '14:00:00'],
        
            ['id_user' => 5, 'day_of_week' => 0, 'start_time' => '09:00:00', 'end_time' => '13:00:00'],
            ['id_user' => 5, 'day_of_week' => 3, 'start_time' => '13:00:00', 'end_time' => '17:00:00'],
            ['id_user' => 5, 'day_of_week' => 6, 'start_time' => '08:00:00', 'end_time' => '12:00:00'],
        
            ['id_user' => 6, 'day_of_week' => 1, 'start_time' => '11:00:00', 'end_time' => '15:00:00'],
            ['id_user' => 6, 'day_of_week' => 5, 'start_time' => '10:00:00', 'end_time' => '14:00:00'],
            ['id_user' => 6, 'day_of_week' => 6, 'start_time' => '15:00:00', 'end_time' => '19:00:00'],
        
            ['id_user' => 1, 'day_of_week' => 2, 'start_time' => '08:00:00', 'end_time' => '10:00:00'],
            ['id_user' => 2, 'day_of_week' => 5, 'start_time' => '14:00:00', 'end_time' => '18:00:00'],
            ['id_user' => 3, 'day_of_week' => 0, 'start_time' => '13:00:00', 'end_time' => '17:00:00'],
            ['id_user' => 4, 'day_of_week' => 6, 'start_time' => '09:00:00', 'end_time' => '13:00:00'],
            ['id_user' => 5, 'day_of_week' => 2, 'start_time' => '14:00:00', 'end_time' => '18:00:00'],
            ['id_user' => 6, 'day_of_week' => 3, 'start_time' => '07:00:00', 'end_time' => '11:00:00'],
            ['id_user' => 1, 'day_of_week' => 6, 'start_time' => '10:00:00', 'end_time' => '12:00:00'],
            ['id_user' => 2, 'day_of_week' => 6, 'start_time' => '15:00:00', 'end_time' => '19:00:00'],
            ['id_user' => 3, 'day_of_week' => 4, 'start_time' => '08:00:00', 'end_time' => '12:00:00'],
            ['id_user' => 4, 'day_of_week' => 1, 'start_time' => '13:00:00', 'end_time' => '17:00:00'],
            ['id_user' => 5, 'day_of_week' => 6, 'start_time' => '11:00:00', 'end_time' => '15:00:00'],
            ['id_user' => 6, 'day_of_week' => 0, 'start_time' => '09:00:00', 'end_time' => '13:00:00'],
        ]);
        
    }
}

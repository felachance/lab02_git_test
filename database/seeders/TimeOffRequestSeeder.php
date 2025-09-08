<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TimeOffRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use existing user and type IDs (adjust if needed)
        $userIds = DB::table('users')->pluck('id')->take(3); // take 3 users
        $typeId = DB::table('time_off_request_types')->value('id'); // use first type

        foreach ($userIds as $index => $userId) {
            DB::table('time_off_requests')->insert([
                'date_start' => Carbon::now()->addDays($index),
                'date_end' => Carbon::now()->addDays($index + 1),
                'hour_start' => '08:00',
                'hour_end' => '17:00',
                'user_id' => $userId,
                'type_id' => $typeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

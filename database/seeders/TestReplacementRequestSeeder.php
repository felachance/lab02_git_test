<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestReplacementRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('replacement_requests')->insert([
            [
                'description' => 'Test Replacement Request 1',
                'id_replacement_request_type' => 1,
                'id_assignment' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'description' => 'Test Replacement Request 2',
                'id_replacement_request_type' => 1,
                'id_assignment' => 2,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'description' => 'Test Replacement Request 3',
                'id_replacement_request_type' => 3,
                'id_assignment' => 3,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'description' => 'Test Replacement Request 3',
                'id_replacement_request_type' => 3,
                'id_assignment' => 4,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'description' => 'Test Replacement Request 3',
                'id_replacement_request_type' => 1,
                'id_assignment' => 5,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ]);
    }
}

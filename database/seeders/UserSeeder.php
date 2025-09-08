<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'code' => '1001',
                'first_name' => 'Alice',
                'last_name' => 'Smith',
                'phone_number' => '(123) 456-7890',
                'birthdate' => '1990-01-01',
                'note' => 'Team leader',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '1002',
                'first_name' => 'Bob',
                'last_name' => 'Johnson',
                'phone_number' => '(234) 567-8901',
                'birthdate' => '1988-02-15',
                'note' => null,
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '1003',
                'first_name' => 'Charlie',
                'last_name' => 'Davis',
                'phone_number' => '(345) 678-9012',
                'birthdate' => '1995-05-20',
                'note' => null,
                'email' => 'charlie@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '1004',
                'first_name' => 'Diana',
                'last_name' => 'Miller',
                'phone_number' => '(456) 789-0123',
                'birthdate' => '1992-10-12',
                'note' => null,
                'email' => 'diana@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '1005',
                'first_name' => 'Ethan',
                'last_name' => 'Wilson',
                'phone_number' => '(567) 890-1234',
                'birthdate' => '1985-07-07',
                'note' => null,
                'email' => 'ethan@example.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '1006',
                'first_name' => 'Geatan',
                'last_name' => 'Levieux',
                'phone_number' => '(678) 901-2345',
                'birthdate' => '1998-03-03',
                'note' => 'Has managerial experience',
                'email' => 'a@a.a',
                'password' => Hash::make('a'),
                'created_at' => now()->subMonths(3),
                'updated_at' => now(),
            ],
        ]);

        // Attach roles to the users
        DB::table('role_user')->insert([
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 2, 'role_id' => 2],
            ['user_id' => 3, 'role_id' => 3],
            ['user_id' => 4, 'role_id' => 2],
            ['user_id' => 5, 'role_id' => 3],
            ['user_id' => 6, 'role_id' => 1],
        ]);
    }
}

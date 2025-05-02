<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MsgSeeder extends Seeder
{
    public function run()
    {
        // Insert data into MsgUsers table
        DB::table('MsgUsers')->insert([
            [
                'user_id' => 'Genesis',
                'email' => 'amanhegde2527@gmail.com',
                'password' => Hash::make('a'),
                'failed_attempts' => 0,
                'lock_until' => 0,
                'last_attempt' => 0,
            ],
            [
                'user_id' => 'a',
                'email' => 'amanhegde2527@gmail.com',
                'password' => Hash::make('a'),
                'failed_attempts' => 0,
                'lock_until' => 0,
                'last_attempt' => 0,
            ],
            [
                'user_id' => 'aa',
                'email' => 'amanhegde2527@gmail.com',
                'password' => Hash::make('a'),
                'failed_attempts' => 0,
                'lock_until' => 0,
                'last_attempt' => 0,
            ],
        ]);

        // Insert data into msg_profile table
        DB::table('msg_profile')->insert([
            [
                'full_name' => 'John Doe',
                'user_id' => 'Genesis',
                'email' => 'amanhegde2527@gmail.com',
                'mobile_number' => '7506202138',
                'gender' => 'Male',
                'dob' => '1990-01-01',
            ],
            [
                'full_name' => 'Jane Smith',
                'user_id' => 'a',
                'email' => 'amanhegde2527@gmail.com',
                'mobile_number' => '0987654321',
                'gender' => 'Female',
                'dob' => '1992-05-15',
            ],
            [
                'full_name' => 'John Doe',
                'user_id' => 'aa',
                'email' => 'amanhegde2527@gmail.com',
                'mobile_number' => '7506202138',
                'gender' => 'Male',
                'dob' => '1990-01-01',
            ],
        ]);
    }
}

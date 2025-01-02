<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Sample data for users
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('v123'), // Use bcrypt to hash passwords
                'mobile_no' => '1234567890',
                'email_verified_at' => Carbon::now(),
                'email_verification_token' => Str::random(32),
                'role' => 'Admin', // or 'admin', etc.
                'membership_status' => 'active',
                'membership_start_date' => Carbon::now()->subMonths(1),
                'membership_end_date' => Carbon::now()->addMonths(11),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
         
        ];

        // Insert users into the database
        DB::table('users')->insert($users);
    }
}

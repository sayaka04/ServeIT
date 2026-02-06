<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder for 'users' table FOR ADMINS
        DB::table('users')->insert([  // ID: 1
            [
                'first_name' => 'Admin1',
                'middle_name' => 'Sample1',
                'last_name' => 'Account1',
                'email' => 'admin1@email.com',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 8909123567,
                'is_admin' => 1,
                'is_technician' => 0,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Admin2', // ID: 2
                'middle_name' => 'Sample2',
                'last_name' => 'Account2',
                'email' => 'admin2@email.com',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 9091235678,
                'is_admin' => 1,
                'is_technician' => 0,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Admin3', // ID: 3
                'middle_name' => 'Sample3',
                'last_name' => 'Account3',
                'email' => 'admin3@email.com',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 1912356780,
                'is_admin' => 1,
                'is_technician' => 0,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);












        // Seeder for 'users' table FOR TECHNICIANS
        DB::table('users')->insert([
            [
                'first_name' => 'Richard', // ID: 4
                'middle_name' => 'A.',
                'last_name' => 'Nygma',
                'email' => 'richard@email.com',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 91234567890,
                'is_admin' => 0,
                'is_technician' => 1,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'June', // ID: 5
                'middle_name' => 'B.',
                'last_name' => 'Rydle',
                'email' => 'june@email.com',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 12345678909,
                'is_admin' => 0,
                'is_technician' => 1,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Jeson', // ID: 6
                'middle_name' => 'C.',
                'last_name' => 'Veilman',
                'email' => 'jeyson@email.com',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 23456789091,
                'is_admin' => 0,
                'is_technician' => 1,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Deniz', // ID: 7
                'middle_name' => 'D.',
                'last_name' => 'Cipher',
                'email' => 'deniz@email.com',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 34567890912,
                'is_admin' => 0,
                'is_technician' => 1,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Mark Paul', // ID: 8
                'middle_name' => 'E.',
                'last_name' => 'Himitsu',
                'email' => 'markpaul@email.com',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 45678909123,
                'is_admin' => 0,
                'is_technician' => 1,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);








        // Seeder for 'users' table FOR ADMINS
        DB::table('users')->insert([ // ID: 9
            [
                'first_name' => 'Jade',
                'middle_name' => 'F.',
                'last_name' => 'Secreta',
                'email' => 'jade@email.com',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 56789091234,
                'is_admin' => 0,
                'is_technician' => 0,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Presa', // ID: 10
                'middle_name' => 'G.',
                'last_name' => 'Hashley',
                'email' => 'presa@gmail.com',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 6789091235,
                'is_admin' => 0,
                'is_technician' => 0,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Patricia', // ID: 11
                'middle_name' => 'H.',
                'last_name' => 'Incognis',
                'email' => 'patricia@umindanao.edu.ph',
                'password' => bcrypt('password'), // Replace 'password' with your actual password
                'phone_number' => 7890912356,
                'is_admin' => 0,
                'is_technician' => 0,
                'is_admin_supervisor' => 0,
                'is_disabled' => 0,
                'is_banned' => 0,
                'profile_picture' => null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

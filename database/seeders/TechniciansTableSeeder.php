<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechniciansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('technicians')->insert([
            [                               // ID: 1
                'technician_user_id' => 4, // Richard
                'technician_code' => 'jRONFFB10RrWY',
                'shop_id' => null,
                'availability_start' => '11:51:36',
                'availability_end' => '12:30:00',
                'address' => 'Bankerohan, Davao City',
                'longitude' => 125.6022850,
                'latitude' => 7.0671610,
                'tesda_verified' => 1,
                'home_service' => 1,
                'tesda_first_four' => 2411,
                'tesda_last_four' => 5536,
                'weighted_score_rating' => 0.00,
                'success_rate' => 0.00,
                'jobs_completed' => 0,
                'banner_picture' => 'technician_banners/default_banner_picture.jpeg',
                'created_at' => Carbon::parse('2025-09-25 12:59:20'),
                'updated_at' => Carbon::parse('2025-10-13 11:47:30'),
            ],
            [                               // ID: 2
                'technician_user_id' => 5, // June
                'technician_code' => 'k5YM14WWNjq7g',
                'shop_id' => null,
                'availability_start' => '02:30:00',
                'availability_end' => '00:00:23',
                'address' => 'Bankerohan, Davao City',
                'longitude' => 125.6018350,
                'latitude' => 7.0666870,
                'tesda_verified' => 1,
                'home_service' => 1,
                'tesda_first_four' => 2211,
                'tesda_last_four' => 9534,
                'weighted_score_rating' => 0.00,
                'success_rate' => 0.00,
                'jobs_completed' => 0,
                'banner_picture' => 'technician_banners/default_banner_picture.jpeg',
                'created_at' => Carbon::parse('2025-09-25 13:42:54'),
                'updated_at' => Carbon::parse('2025-10-09 11:56:48'),
            ],

            [                               // ID: 3
                'technician_user_id' => 6, // Jeson
                'technician_code' => 'l5U9IBGwqQ9Dr',
                'shop_id' => null,
                'availability_start' => '20:23:23',
                'availability_end' => '23:00:00',
                'address' => 'Datu Bago Street, Bankerohan, Davao City',
                'longitude' => 125.6024190,
                'latitude' => 7.0663330,
                'tesda_verified' => 1,
                'home_service' => 1,
                'tesda_first_four' => 2211,
                'tesda_last_four' => 9070,
                'weighted_score_rating' => 0.00,
                'success_rate' => 0.00,
                'jobs_completed' => 0,
                'banner_picture' => 'technician_banners/default_banner_picture.jpeg',
                'created_at' => Carbon::parse('2025-09-25 14:41:58'),
                'updated_at' => Carbon::parse('2025-09-25 15:04:03'),
            ],
            [                               // ID: 4
                'technician_user_id' => 7, // Deniz
                'technician_code' => 'mOHQENKAx5A2n',
                'shop_id' => null,
                'availability_start' => '01:20:30',
                'availability_end' => '11:20:30',
                'address' => 'Mac Arthur Highway, Matina, Davao City',
                'longitude' => 125.6003810,
                'latitude' => 7.0637520,
                'tesda_verified' => 1,
                'home_service' => 0,
                'tesda_first_four' => 2211,
                'tesda_last_four' => 9076,
                'weighted_score_rating' => 0.00,
                'success_rate' => 0.00,
                'jobs_completed' => 0,
                'banner_picture' => 'technician_banners/default_banner_picture.jpeg',
                'created_at' => Carbon::parse('2025-09-25 15:32:01'),
                'updated_at' => Carbon::parse('2025-10-09 12:16:17'),
            ],
            [                               // ID: 5
                'technician_user_id' => 8, // Mark
                'technician_code' => 'nRJA0PN7A576L',
                'shop_id' => null,
                'availability_start' => '01:20:30',
                'availability_end' => '10:20:30',
                'address' => 'E. Quirino Avenue, Davao City',
                'longitude' => 125.6041680,
                'latitude' => 7.0695890,
                'tesda_verified' => 0,
                'home_service' => 1,
                'tesda_first_four' => 0,
                'tesda_last_four' => 0,
                'weighted_score_rating' => 0.00,
                'success_rate' => 0.00,
                'jobs_completed' => 0,
                'banner_picture' => 'technician_banners/default_banner_picture.jpeg',
                'created_at' => Carbon::parse('2025-09-25 15:34:28'),
                'updated_at' => Carbon::parse('2025-10-09 12:16:38'),
            ],
        ]);
    }
}

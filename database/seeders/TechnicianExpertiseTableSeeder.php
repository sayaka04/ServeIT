<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechnicianExpertiseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('technician_expertise')->insert([
            [
                'technician_id' => 1,
                'expertise_category_id' => 19,
                'is_archived' => 1,
                'created_at' => '2025-10-08 20:04:34',
                'updated_at' => '2025-10-08 20:11:41',
            ],
            [
                'technician_id' => 1,
                'expertise_category_id' => 20,
                'is_archived' => 1,
                'created_at' => '2025-10-08 20:04:34',
                'updated_at' => '2025-10-08 12:15:01',
            ],
            [
                'technician_id' => 1,
                'expertise_category_id' => 1,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 1,
                'expertise_category_id' => 2,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 1,
                'expertise_category_id' => 3,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 1,
                'expertise_category_id' => 5,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 1,
                'expertise_category_id' => 21,
                'is_archived' => 1,
                'created_at' => '2025-10-08 20:11:34',
                'updated_at' => '2025-10-08 12:15:01',
            ],
        ]);







        DB::table('technician_expertise')->insert([
            [
                'technician_id' => 2,
                'expertise_category_id' => 19,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:04:34',
                'updated_at' => '2025-10-08 20:11:41',
            ],
            [
                'technician_id' => 2,
                'expertise_category_id' => 20,
                'is_archived' => 1,
                'created_at' => '2025-10-08 20:04:34',
                'updated_at' => '2025-10-08 12:15:01',
            ],
            [
                'technician_id' => 2,
                'expertise_category_id' => 2,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 2,
                'expertise_category_id' => 3,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
        ]);




        DB::table('technician_expertise')->insert([
            [
                'technician_id' => 3,
                'expertise_category_id' => 19,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:04:34',
                'updated_at' => '2025-10-08 20:11:41',
            ],
            [
                'technician_id' => 3,
                'expertise_category_id' => 20,
                'is_archived' => 1,
                'created_at' => '2025-10-08 20:04:34',
                'updated_at' => '2025-10-08 12:15:01',
            ],
            [
                'technician_id' => 3,
                'expertise_category_id' => 2,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 3,
                'expertise_category_id' => 3,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
        ]);



        DB::table('technician_expertise')->insert([
            [
                'technician_id' => 4,
                'expertise_category_id' => 19,
                'is_archived' => 1,
                'created_at' => '2025-10-08 20:04:34',
                'updated_at' => '2025-10-08 20:11:41',
            ],
            [
                'technician_id' => 4,
                'expertise_category_id' => 20,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:04:34',
                'updated_at' => '2025-10-08 12:15:01',
            ],
            [
                'technician_id' => 4,
                'expertise_category_id' => 1,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 4,
                'expertise_category_id' => 2,
                'is_archived' => 1,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 4,
                'expertise_category_id' => 3,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 4,
                'expertise_category_id' => 5,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 4,
                'expertise_category_id' => 21,
                'is_archived' => 1,
                'created_at' => '2025-10-08 20:11:34',
                'updated_at' => '2025-10-08 12:15:01',
            ],
        ]);




        DB::table('technician_expertise')->insert([
            [
                'technician_id' => 5,
                'expertise_category_id' => 19,
                'is_archived' => 1,
                'created_at' => '2025-10-08 20:04:34',
                'updated_at' => '2025-10-08 20:11:41',
            ],
            [
                'technician_id' => 5,
                'expertise_category_id' => 1,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 5,
                'expertise_category_id' => 2,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 5,
                'expertise_category_id' => 3,
                'is_archived' => 1,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
            [
                'technician_id' => 5,
                'expertise_category_id' => 5,
                'is_archived' => 0,
                'created_at' => '2025-10-08 20:06:42',
                'updated_at' => '2025-10-08 20:06:42',
            ],
        ]);
    }
}

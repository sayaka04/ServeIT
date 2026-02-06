<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechnicianFilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('technician_files')->insert([
            [
                'technician_id' => 1,
                'file_name' => 'Tesda Certificate',
                'file_description' => 'Proof of certification',
                'file_type' => 'png',
                'file_path' => 'technician_files/FyN097wvTMsRBCLiXrjvQZS0p99ElZJ7s9k8chE4.png',
                'is_deleted' => 0,
                'created_at' => '2025-09-25 13:01:10',
                'updated_at' => '2025-09-25 13:01:10',
            ],
            [
                'technician_id' => 2,
                'file_name' => 'Tesda Certificate',
                'file_description' => 'Certificate',
                'file_type' => 'png',
                'file_path' => 'technician_files/BmtoQpZkJmY5bgL1hpZnTilm8KXVqbsSfHHBDU3f.png',
                'is_deleted' => 0,
                'created_at' => '2025-09-25 14:02:46',
                'updated_at' => '2025-09-25 14:02:46',
            ],
            [
                'technician_id' => 3,
                'file_name' => 'Tesda Certificate',
                'file_description' => 'Certificate',
                'file_type' => 'png',
                'file_path' => 'technician_files/0II4Vm1Js8yWd3q3XWtJlfcyjbbVOdPilrYS9wsv.png',
                'is_deleted' => 0,
                'created_at' => '2025-09-25 14:49:45',
                'updated_at' => '2025-09-25 14:49:45',
            ],
        ]);
    }
}

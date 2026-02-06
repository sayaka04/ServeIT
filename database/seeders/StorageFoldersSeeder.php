<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StorageFoldersSeeder extends Seeder
{
    public function run(): void
    {


        $folders = [
            'files',
            'images',
            'img',
            'pdf',
            'profile_pictures',
            'progress',
            'repair-orders',
            'repair_received_files',
            'reports',
            'signatures',
            'technician_banners',
            'technician_files',
            'uploads',
            'seed-files',
        ];

        foreach ($folders as $folder) {
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }
        }


        Storage::disk('public')->put(
            'repair-orders/repair_order_sample.pdf',
            file_get_contents(resource_path('defaults/repair_order_sample.pdf'))
        );

        Storage::disk('public')->put(
            'signatures/sign_sample.jpeg',
            file_get_contents(resource_path('defaults/sign_sample.jpeg'))
        );

        Storage::disk('public')->put(
            'profile_pictures/default_profile_picture.jpeg',
            file_get_contents(resource_path('defaults/default_profile_picture.png'))
        );

        Storage::disk('public')->put(
            'technician_banners/default_banner_picture.jpeg',
            file_get_contents(resource_path('defaults/default_banner_picture.png'))
        );

        Storage::disk('public')->put(
            'technician_files/default_tesda_cert.jpeg',
            file_get_contents(resource_path('defaults/default_tesda_cert.jpeg'))
        );
    }
}

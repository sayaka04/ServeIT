<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpertiseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // ---------------------------------------------
            // 1. Device Type / Hardware Focus
            // ---------------------------------------------
            [
                'name' => 'Mobile Phone/Tablet Hardware',
                'description' => 'Component-level repair (screen, battery, charging port, camera, chassis).',
            ],
            [
                'name' => 'Laptop Hardware Repair',
                'description' => 'Motherboard repair, screen replacement, hinge repair, component soldering.',
            ],
            [
                'name' => 'Desktop PC Hardware',
                'description' => 'Building, upgrading, troubleshooting, component replacement (PSU, GPU, CPU, etc.).',
            ],
            [
                'name' => 'Game Console Repair',
                'description' => 'Fixing popular consoles (e.g., PS5, Xbox, Nintendo Switch).',
            ],
            [
                'name' => 'Wearable Tech / Smartwatch',
                'description' => 'Repair for smartwatches, fitness bands, and other wearable technology.',
            ],

            // ---------------------------------------------
            // 2. Operating System / Platform Focus
            // ---------------------------------------------
            [
                'name' => 'Android OS Specialist',
                'description' => 'Troubleshooting, flashing, rooting, OS installation, and software issues for Android devices.',
            ],
            [
                'name' => 'iOS / Apple Specialist',
                'description' => 'Diagnosis, software updates, data recovery, and repairs specific to iPhone/iPad/MacOS ecosystems.',
            ],
            [
                'name' => 'Windows OS Specialist',
                'description' => 'Troubleshooting, blue screens, driver issues, OS reinstallations, and recovery.',
            ],
            [
                'name' => 'MacOS Specialist',
                'description' => 'Expertise in Apple desktop/laptop operating systems and related software.',
            ],
            [
                'name' => 'Linux / Server Setup',
                'description' => 'Expertise in Linux operating systems and setting up local servers or networks.',
            ],

            // ---------------------------------------------
            // 3. Specialized & Cross-Platform Skills
            // ---------------------------------------------
            [
                'name' => 'Data Recovery Expert',
                'description' => 'Retrieving data from failed/damaged hard drives, SSDs, and mobile devices.',
            ],
            [
                'name' => 'BGA / Micro-Soldering',
                'description' => 'Advanced repair of motherboard components (IC chips, trace repair) at a microscopic level.',
            ],
            [
                'name' => 'Water Damage Restoration',
                'description' => 'Cleaning and restoring devices that have suffered liquid damage.',
            ],
            [
                'name' => 'Networking & Wi-Fi Setup',
                'description' => 'Router configuration, network troubleshooting, mesh systems, and connectivity issues.',
            ],
            [
                'name' => 'Malware / Virus Removal',
                'description' => 'Cleaning infected computers and mobile devices and protecting against future attacks.',
            ],

            // ---------------------------------------------
            // 4. Smart Home & Office Tech
            // ---------------------------------------------
            [
                'name' => 'Smart TV & Home Theater',
                'description' => 'Setup, repair, firmware updates, and troubleshooting for Smart TVs and audio systems.',
            ],
            [
                'name' => 'CCTV & Security Systems',
                'description' => 'Installation, configuration, and troubleshooting of surveillance cameras and home alarm systems.',
            ],
            [
                'name' => 'Printer & Scanner Repair',
                'description' => 'Fixing jams, connectivity issues, replacing components, and driver setup for home and office printers.',
            ],
            [
                'name' => 'Data Backup & Cloud Setup',
                'description' => 'Implementing reliable backup solutions and data migration services.',
            ],

            // ---------------------------------------------
            // 5. Philippines-Specific/High Demand Skills
            // ---------------------------------------------
            [
                'name' => 'Custom PC Building/Modding',
                'description' => 'Expertise in assembling and customizing high-performance desktop computers for gaming or editing.',
            ],
            [
                'name' => 'Point-of-Sale (POS) Systems',
                'description' => 'Setup, troubleshooting, and maintenance of specialized POS hardware and software for businesses.',
            ],
            [
                'name' => 'Fiber Optic/ISP Troubleshooting',
                'description' => 'Diagnosing issues related to modem, router, and ISP connections (PLDT, Globe, Converge).',
            ],
            [
                'name' => 'Account/Cloud Unlock & Recovery',
                'description' => 'Recovering access to locked devices (FRP lock, iCloud lock) and online accounts.',
            ],
            [
                'name' => 'Software Installation & Licensing',
                'description' => 'Installing operating systems, office suites, and managing software licenses.',
            ],
            [
                'name' => 'Preventive Maintenance (PM)',
                'description' => 'General cleaning, thermal paste replacement, and internal checks to extend device life.',
            ],
        ];

        // Insert the data into the expertise_categories table
        DB::table('expertise_categories')->insert($categories);
    }
}

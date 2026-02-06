<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\ExpertiseCategory;
use App\Models\Technician;
use App\Models\TechnicianFile;
use App\Models\TechnicianLink;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $this->call(StorageFoldersSeeder::class);



        $this->call([
            ReportCategoriesSeeder::class,
            ExpertiseCategorySeeder::class,

            UsersTableSeeder::class,
            TechniciansTableSeeder::class,


            TechnicianExpertiseTableSeeder::class,
            TechnicianFilesTableSeeder::class,
            TechnicianLinksTableSeeder::class,


            ConversationsSeeder::class,
        ]);
    }
}

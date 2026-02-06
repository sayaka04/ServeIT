<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fraud', 'description' => 'False representation or deceitful actions to scam users.'],
            ['name' => 'Scam', 'description' => 'Attempting to take money without providing services.'],
            ['name' => 'Impersonation', 'description' => 'Pretending to be someone else or using a fake identity.'],
            ['name' => 'Abuse', 'description' => 'Verbal, written, or other forms of aggressive behavior.'],
            ['name' => 'Harassment', 'description' => 'Unwanted and inappropriate contact or behavior.'],
            ['name' => 'Fake Reviews', 'description' => 'Posting or soliciting misleading or fake feedback.'],
            ['name' => 'Discrimination', 'description' => 'Unfair treatment based on race, gender, religion, etc.'],
            ['name' => 'Unprofessional Conduct', 'description' => 'Rude or inappropriate behavior during service.'],
            ['name' => 'Privacy Violation', 'description' => 'Sharing or misusing personal information.'],
            ['name' => 'Misrepresentation', 'description' => 'Providing false information, credentials, or experience.'],
            ['name' => 'Other', 'description' => 'Issues that do not fit into predefined categories.'],
        ];

        foreach ($categories as $category) {
            DB::table('report_categories')->insert([
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

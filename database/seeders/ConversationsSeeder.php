<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConversationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        include __DIR__ . '/partials/flow1/flow1_v1.php';
        include __DIR__ . '/partials/flow1/flow1_v2.php';
        include __DIR__ . '/partials/flow1/flow1_v3.php';

        include __DIR__ . '/partials/flow2/flow2_v1.php';
        include __DIR__ . '/partials/flow2/flow2_v2.php';
        include __DIR__ . '/partials/flow2/flow2_v3.php';


        include __DIR__ . '/partials/flow3/flow3_v1.php';
        include __DIR__ . '/partials/flow3/flow3_v2.php';
        include __DIR__ . '/partials/flow3/flow3_v3.php';
    }
}

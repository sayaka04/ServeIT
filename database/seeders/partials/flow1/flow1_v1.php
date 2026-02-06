<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

// --------------------------------------------------------------------------------------------------------------------
// CONFIGURATION
// --------------------------------------------------------------------------------------------------------------------
$user_id = 9;
$user_name = 'Jade F. Secreta';
$technician_user_id = 4;
$technician_name = 'Richard A. Nygma';
$technician_id = 1;
$conversation_code = 'jRCURDnG4NsddsaggP';

$device = 'Asus Zenbook 14 OLED (2024)';
$device_type = 'Laptop';
$issue_message = 'My laptop screen doesnt turn on, althought the power power does light up, fan also works no problem?';
$issues = '[{"issue":"Broken Screen LCD"}]';
$breakdown = '[{"item":"Screen Replacement","price":2000},{"item":"Repair Fee","price":500}]';
$cost = 2500.00;

// TIMELINE SETUP
$starting_week = -3; // 1 week ago
$base = now()->addWeeks($starting_week);

// --------------------------------------------------------------------------------------------------------------------
// 1. CONVERSATION CREATION (The Start)
// --------------------------------------------------------------------------------------------------------------------
$conversation_id = DB::table('conversations')->insertGetId([
    'conversation_code' => $conversation_code,
    'user_id' => $user_id,
    'technician_user_id' => $technician_user_id,
    'technician_id' => $technician_id,
    'topic' => null,
    'c_last_seen' => now(),
    't_last_seen' => now(),
    'c_is_notified' => 0,
    't_is_notified' => 0,
    'created_at' => $base->copy(),
    'updated_at' => $base->copy(),
]);

// --------------------------------------------------------------------------------------------------------------------
// 2. INQUIRY PHASE (Chatting Online)
// --------------------------------------------------------------------------------------------------------------------

// Client says Hello
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $user_id,
    'message'              => Crypt::encryptString('Hello po'),
    'created_at'           => $base->copy()->addMinutes(1),
    'updated_at'           => $base->copy()->addMinutes(1),
]);

// Tech Replies
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $technician_user_id,
    'message'              => Crypt::encryptString('Hello! How can I help you?'),
    'created_at'           => $base->copy()->addMinutes(2),
    'updated_at'           => $base->copy()->addMinutes(2),
]);

// Client explains issue
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $user_id,
    'message'              => Crypt::encryptString($issue_message),
    'created_at'           => $base->copy()->addMinutes(5),
    'updated_at'           => $base->copy()->addMinutes(5),
]);

// Tech asks for device details
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $technician_user_id,
    'message'              => Crypt::encryptString('May I know the device brand and specs?'),
    'created_at'           => $base->copy()->addMinutes(10),
    'updated_at'           => $base->copy()->addMinutes(10),
]);

// Client gives details
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $user_id,
    'message'              => Crypt::encryptString($device),
    'created_at'           => $base->copy()->addMinutes(12),
    'updated_at'           => $base->copy()->addMinutes(12),
]);

// --------------------------------------------------------------------------------------------------------------------
// 3. NEGOTIATION (Before the Repair is Created)
// --------------------------------------------------------------------------------------------------------------------

// Tech asks client to bring it in
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $technician_user_id,
    'message'              => Crypt::encryptString('Please bring the device to the shop so I can run a diagnostic.'),
    'created_at'           => $base->copy()->addMinutes(15),
    'updated_at'           => $base->copy()->addMinutes(15),
]);

// Client agrees to come (THIS WAS THE MISSING LINK IN THE TIMELINE)
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $user_id,
    'message'              => Crypt::encryptString('Okay, I will be there in a while.'),
    'created_at'           => $base->copy()->addMinutes(16),
    'updated_at'           => $base->copy()->addMinutes(16),
]);

// *** TIME JUMP: 2 HOURS LATER (Client Travels to Shop) ***
$arrival_time = $base->copy()->addHours(2);

// --------------------------------------------------------------------------------------------------------------------
// 4. REPAIR CREATION (Technician Physically Receives Device)
// --------------------------------------------------------------------------------------------------------------------

// Create the Repair Record (Status is initially 'pending' or 'in_progress', not completed yet)
$repair_id = DB::table('repairs')->insertGetId([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_id'        => $technician_id,
    'status'               => 'completed', // This represents the FINAL state of the DB row, which is fine for a seeder
    'device'               => $device,
    'device_type'          => $device_type,
    'receive_notes'        => 'Received device with minor scratches on lid.',
    'is_received'          => 1,
    'is_completed'         => 1,
    'completion_confirmed' => 1,
    'is_claimed'           => 1,

    'order_slip_path' => 'repair-orders/repair_order_sample.pdf',
    'confirmation_signature_path' => 'signatures/sign_sample.jpeg',
    'claim_signature_path' => 'signatures/sign_sample.jpeg',

    'issues'               => $issues,
    'breakdown'            => $breakdown,
    'estimated_cost'       => $cost,
    'serial_number'        => 'N9P0BN081478107',
    'client_final_confirmation' => 1,
    'completion_date'      => $arrival_time->copy()->addDays(2),
    'created_at'           => $arrival_time, // CREATED AFTER ARRIVAL
    'updated_at'           => $arrival_time,
]);

// --------------------------------------------------------------------------------------------------------------------
// 5. SYSTEM MESSAGES (The Blue Card)
// --------------------------------------------------------------------------------------------------------------------

// This inserts the "Repair Info" card into the chat
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'repair_id'            => $repair_id, // This triggers the card UI
    'sender_id'            => $technician_user_id, // Tech creates the order
    'message'              => Crypt::encryptString('*'), // System marker
    'created_at'           => $arrival_time->copy()->addMinutes(5), // 5 mins after physical arrival
    'updated_at'           => $arrival_time->copy()->addMinutes(5),
]);



// --------------------------------------------------------------------------------------------------------------------
// 6. PROGRESS UPDATES (Over the next 2 days)
// --------------------------------------------------------------------------------------------------------------------

$repair_progress_status = ['Ordered Arrived!', 'Repair Complete!'];
$repair_progress_description = ['Changing the Screen', 'Screen replaced and tested.'];
$repair_progress_days = [1, 2]; // Days after arrival

for ($i = 0; $i < count($repair_progress_status); $i++) {
    $progress_time = $arrival_time->copy()->addDays($repair_progress_days[$i]);

    DB::table('repair_progress')->insert([
        'repair_id'       => $repair_id,
        'description'     => $repair_progress_description[$i],
        'completion_rate' => ($i + 1) * 50,
        'progress_status' => $repair_progress_status[$i],
        'created_at'      => $progress_time,
        'updated_at'      => $progress_time,
    ]);

    // Notification for client
    DB::table('notifications')->insert([
        'recipient'       => $user_id,
        'subject'         => 'Update: ' . $repair_progress_status[$i],
        'description'     => $repair_progress_description[$i],
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id'   => $repair_id,
        'has_seen'        => 1,
        'created_at'      => $progress_time,
        'updated_at'      => $progress_time,
    ]);
}

// --------------------------------------------------------------------------------------------------------------------
// 7. CLAIMING & CLOSING (Day 2 After Repair Complete)
// --------------------------------------------------------------------------------------------------------------------

$completion_time = $arrival_time->copy()->addDays(2)->addHours(2);

// Tech says it's done
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $technician_user_id,
    'message'              => Crypt::encryptString('Hi, the repair is finished. You may claim it now.'),
    'created_at'           => $completion_time,
    'updated_at'           => $completion_time,
]);

// Client replies
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $user_id,
    'message'              => Crypt::encryptString('Brilliant! I will drop by later.'),
    'created_at'           => $completion_time->copy()->addMinutes(10),
    'updated_at'           => $completion_time->copy()->addMinutes(10),
]);

// --------------------------------------------------------------------------------------------------------------------
// 8. FINAL RATINGS (Only happens after claiming)
// --------------------------------------------------------------------------------------------------------------------

DB::table('repair_ratings')->insert([
    'repair_id'                 => $repair_id,
    'user_id'                   => $user_id,
    'technician_id'             => $technician_id,
    'user_weighted_score'       => 100.00,
    'user_comment'              => 'Fantastic job!',
    'technician_weighted_score' => 100.00,
    'technician_comment'        => 'Easy to deal with.',
    'created_at'                => $completion_time->copy()->addHours(4), // Rated after pickup
    'updated_at'                => $completion_time->copy()->addHours(4),
]);

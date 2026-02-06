<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

// --------------------------------------------------------------------------------------------------------------------
// CONFIGURATION
// --------------------------------------------------------------------------------------------------------------------
$user_id = 10;
$user_name = 'Presa G. Hashley';
$technician_user_id = 5;
$technician_name = 'June B. Rydle';
$technician_id = 2;
$conversation_code = 'SJekdYIsmnds';

$device = 'Asus Zenbook 14 OLED (2024)';
$device_type = 'Laptop';
$issue_message = 'My laptop screen doesnt turn on, althought the power power does light up, fan also works no problem?';
$issues = '[{"issue":"Broken Screen LCD"}]';
$breakdown = '[{"item":"Screen Replacement","price":2000},{"item":"Repair Fee","price":500}]';
$cost = 2500.00;

$repair_progress_status = ['Ordered Arrived!', 'Repair Complete!'];
$repair_progress_description = [
    'Changing the Screen',
    'The screen has been replaced, and device is now functioning without issues.'
];
$repair_progress_completion_rate = [50, 100];
$repair_progress_days = [1, 2];

$starting_week = -3;
$base = now()->addWeeks($starting_week);
$last_day = 2;

// --------------------------------------------------------------------------------------------------------------------
// 1. CONVERSATION CREATION
// --------------------------------------------------------------------------------------------------------------------
$conversation_id = DB::table('conversations')->insertGetId([
    'conversation_code' => $conversation_code,
    'user_id' => $user_id,
    'technician_user_id' => $technician_user_id,
    'technician_id' => $technician_id,
    'topic' => null,
    'c_last_seen' => '2025-09-25 14:33:23',
    't_last_seen' => '2025-10-13 12:33:09',
    'c_is_notified' => 0,
    't_is_notified' => 0,
    'created_at' => $base->copy(),
    'updated_at' => $base->copy(),
]);

// --------------------------------------------------------------------------------------------------------------------
// 2. INQUIRY PHASE (Digital Chat Only)
// --------------------------------------------------------------------------------------------------------------------

DB::table('notifications')->insert([
    [
        'recipient'       => $technician_user_id,
        'subject'         => 'New Message from ' . $user_name,
        'description'     => '',
        'notifiable_type' => 'App\Models\Conversation',
        'notifiable_id'   => $conversation_id,
        'has_seen'        => 1,
        'created_at'      => $base->copy()->addMinutes(1),
        'updated_at'      => $base->copy()->addMinutes(1),
    ],
]);

DB::table('conversation_messages')->insert([
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $user_id,
        'message'            => Crypt::encryptString('Hello po'),
        'created_at'         => $base->copy()->addMinutes(1),
        'updated_at'         => $base->copy()->addMinutes(1),
        'repair_accepted'    => null,
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
    ],
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $technician_user_id,
        'message'            => Crypt::encryptString('Hello! How can I help you?'),
        'created_at'         => $base->copy()->addMinutes(2),
        'updated_at'         => $base->copy()->addMinutes(2),
        'repair_accepted'    => null,
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
    ],
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $user_id,
        'message'            => Crypt::encryptString($issue_message),
        'created_at'         => $base->copy()->addMinutes(3),
        'updated_at'         => $base->copy()->addMinutes(3),
        'repair_accepted'    => null,
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
    ],
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $technician_user_id,
        'message'            => Crypt::encryptString('May I know the device brand and its specs?'),
        'created_at'         => $base->copy()->addMinutes(4),
        'updated_at'         => $base->copy()->addMinutes(4),
        'repair_accepted'    => null,
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
    ],
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $user_id,
        'message'            => Crypt::encryptString($device),
        'created_at'         => $base->copy()->addMinutes(5),
        'updated_at'         => $base->copy()->addMinutes(5),
        'repair_accepted'    => null,
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
    ],
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $technician_user_id,
        'message'            => Crypt::encryptString('Please bring the device to the shop so I can run a diagnostic.'),
        'created_at'         => $base->copy()->addMinutes(6),
        'updated_at'         => $base->copy()->addMinutes(6),
        'repair_accepted'    => null,
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
    ],
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $user_id,
        'message'            => Crypt::encryptString('Okay, I will be there in a while.'),
        'created_at'         => $base->copy()->addMinutes(7),
        'updated_at'         => $base->copy()->addMinutes(7),
        'repair_accepted'    => null,
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
    ],
]);

// *** TIME JUMP: 2 HOURS LATER (Client arrives at shop) ***
$arrival_time = $base->copy()->addHours(2);

// --------------------------------------------------------------------------------------------------------------------
// 3. REPAIR INITIATION (Now that the device is physically present)
// --------------------------------------------------------------------------------------------------------------------
$repair_id = DB::table('repairs')->insertGetId([
    'conversation_id'           => $conversation_id,
    'user_id'                   => $user_id,
    'technician_id'             => $technician_id,
    'status'                    => 'completed',
    'device'                    => $device,
    'device_type'               => $device_type,
    'receive_notes'             => 'Received device. Physical inspection complete. Screen needs replacement.',
    'is_received'               => 1,
    'is_completed'              => 1,
    'completion_confirmed'      => 1,
    'is_claimed'                => 1,
    'issues'                    => $issues,
    'breakdown'                 => $breakdown,
    'estimated_cost'            => $cost,
    'serial_number'             => 'N9P0BN081478107',
    'client_final_confirmation' => 1,
    'order_slip_path'           => 'repair-orders/repair_order_sample.pdf',
    'confirmation_signature_path' => 'signatures/sign_sample.jpeg',
    'claim_signature_path'      => 'signatures/sign_sample.jpeg',
    'completion_date'           => $arrival_time->copy()->addDays(2),
    'created_at'                => $arrival_time,
    'updated_at'                => $arrival_time,
]);

// 4. THE REPAIR CARD (*) - Sent by Tech after creating record
DB::table('conversation_messages')->insert([
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => $repair_id,
        'sender_id'          => $technician_user_id,
        'message'            => Crypt::encryptString('*'),
        'created_at'         => $arrival_time->copy()->addMinutes(5),
        'updated_at'         => $arrival_time->copy()->addMinutes(5),
        'repair_accepted'    => null,
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
    ],
]);

// --------------------------------------------------------------------------------------------------------------------
// 5. PROGRESS & NOTIFICATIONS
// --------------------------------------------------------------------------------------------------------------------

for ($i = 0; $i < count($repair_progress_status); $i++) {
    $p_time = $arrival_time->copy()->addDays($repair_progress_days[$i]);

    DB::table('repair_progress')->insert([
        'repair_id'       => $repair_id,
        'description'     => $repair_progress_description[$i],
        'completion_rate' => $repair_progress_completion_rate[$i],
        'progress_status' => $repair_progress_status[$i],
        'created_at'      => $p_time,
        'updated_at'      => $p_time,
    ]);

    DB::table('notifications')->insert([
        'recipient'       => $user_id,
        'subject'         => ($repair_progress_completion_rate[$i] == 100) ? 'Repair Completed!' : 'Repair Progress Added: ' . $repair_progress_status[$i],
        'description'     => $repair_progress_description[$i],
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id'   => $repair_id,
        'has_seen'        => 1,
        'created_at'      => $p_time,
        'updated_at'      => $p_time,
    ]);
}

// --------------------------------------------------------------------------------------------------------------------
// 6. FINAL CHAT & RATINGS
// --------------------------------------------------------------------------------------------------------------------
$completion_msg_time = $arrival_time->copy()->addDays(2)->addMinutes(30);

DB::table('conversation_messages')->insert([
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id'          => $technician_user_id,
        'message'            => Crypt::encryptString('Hi, I have finished the repair, you may claim it now.'),
        'created_at'         => $completion_msg_time,
        'updated_at'         => $completion_msg_time,
        'repair_id' => null,
        'repair_accepted' => null,
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
    ],
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id'          => $user_id,
        'message'            => Crypt::encryptString('Brilliant! Thanks, ill claim later after work.'),
        'created_at'         => $completion_msg_time->copy()->addMinutes(10),
        'updated_at'         => $completion_msg_time->copy()->addMinutes(10),
        'repair_id' => null,
        'repair_accepted' => null,
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
    ],
]);

DB::table('repair_ratings')->insert([
    [
        'repair_id'                 => $repair_id,
        'user_id'                   => $user_id,
        'technician_id'             => $technician_id,
        'user_weighted_score'       => 100.00,
        'user_comment'              => 'The Technician did a fantastic job, no complaints here!',
        'technician_weighted_score' => 100.00,
        'technician_comment'        => 'The client is easy to talk to and is direct.',
        'created_at'                => $completion_msg_time->copy()->addHours(5),
        'updated_at'                => $completion_msg_time->copy()->addHours(5),
    ],
]);

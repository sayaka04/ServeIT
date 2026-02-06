<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

// --------------------------------------------------------------------------------------------------------------------
// CONFIGURATION & SEED DATA
// --------------------------------------------------------------------------------------------------------------------
$user_id = 11;
$user_name = 'Patricia H. Incognis';
$technician_user_id = 7;
$technician_name = 'Deniz D. Cipher';
$technician_id = 3;
$conversation_code = 'SJTWMsbhIShf';

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
// 1. CONVERSATION INITIALIZATION
// --------------------------------------------------------------------------------------------------------------------
$conversation_id = DB::table('conversations')->insertGetId([
    'conversation_code' => $conversation_code,
    'user_id' => $user_id,
    'technician_user_id' => $technician_user_id,
    'technician_id' => $technician_id,
    'topic' => null,
    'c_last_seen' => $base->copy()->addDays(2),
    't_last_seen' => $base->copy()->addDays(2),
    'c_is_notified' => 0,
    't_is_notified' => 0,
    'created_at' => $base->copy(),
    'updated_at' => $base->copy(),
]);

// --------------------------------------------------------------------------------------------------------------------
// 2. INQUIRY PHASE
// --------------------------------------------------------------------------------------------------------------------
DB::table('notifications')->insert([
    [
        'recipient' => $technician_user_id,
        'subject' => 'New Message from ' . $user_name,
        'description' => 'Inquiry regarding screen issues.',
        'notifiable_type' => 'App\Models\Conversation',
        'notifiable_id' => $conversation_id,
        'has_seen' => 1,
        'created_at' => $base->copy()->addMinutes(1),
        'updated_at' => $base->copy()->addMinutes(1),
    ],
]);

DB::table('conversation_messages')->insert([
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $user_id,
        'message' => Crypt::encryptString('Hello po'),
        'created_at' => $base->copy()->addMinutes(1),
        'updated_at' => $base->copy()->addMinutes(1),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $technician_user_id,
        'message' => Crypt::encryptString('Hello! How can I help you?'),
        'created_at' => $base->copy()->addMinutes(2),
        'updated_at' => $base->copy()->addMinutes(2),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $user_id,
        'message' => Crypt::encryptString($issue_message),
        'created_at' => $base->copy()->addMinutes(3),
        'updated_at' => $base->copy()->addMinutes(3),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $technician_user_id,
        'message' => Crypt::encryptString('May I know the device brand and its specs?'),
        'created_at' => $base->copy()->addMinutes(4),
        'updated_at' => $base->copy()->addMinutes(4),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $user_id,
        'message' => Crypt::encryptString($device),
        'created_at' => $base->copy()->addMinutes(5),
        'updated_at' => $base->copy()->addMinutes(5),
    ],
]);

// --------------------------------------------------------------------------------------------------------------------
// 3. REPAIR RECORD & BLUE CARD (Sent by Technician)
// --------------------------------------------------------------------------------------------------------------------
$repair_id = DB::table('repairs')->insertGetId([
    'conversation_id' => $conversation_id,
    'user_id' => $user_id,
    'technician_id' => $technician_id,
    'status' => 'completed',
    'device' => $device,
    'device_type' => $device_type,
    'receive_notes' => 'I have received device without any apparent external issues major scratches and etc.',
    'is_received' => 1,
    'is_completed' => 1,
    'completion_confirmed' => 1,
    'is_claimed' => 1,
    'issues' => $issues,
    'breakdown' => $breakdown,
    'estimated_cost' => $cost,
    'serial_number' => 'N9P0BN081478107',
    'client_final_confirmation' => 1,
    'order_slip_path'           => 'repair-orders/repair_order_sample.pdf',
    'confirmation_signature_path' => 'signatures/sign_sample.jpeg',
    'claim_signature_path'      => 'signatures/sign_sample.jpeg',
    'completion_date' => $base->copy()->addDays(3),
    'created_at' => $base->copy()->addMinutes(6),
    'updated_at' => $base->copy()->addMinutes(6),
]);

// Blue Card from Technician
DB::table('conversation_messages')->insert([
    'conversation_id' => $conversation_id,
    'user_id' => $user_id,
    'technician_user_id' => $technician_user_id,
    'repair_id' => $repair_id,
    'sender_id' => $technician_user_id,
    'message' => Crypt::encryptString('*'),
    'created_at' => $base->copy()->addMinutes(6),
    'updated_at' => $base->copy()->addMinutes(6),
]);

// --------------------------------------------------------------------------------------------------------------------
// 4. PROGRESS & NOTIFICATIONS
// --------------------------------------------------------------------------------------------------------------------
DB::table('notifications')->insert([
    [
        'recipient' => $user_id,
        'subject' => 'Breakdown for your repair sent!',
        'description' => 'Your repair breakdown has been sent by ' . $technician_name,
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id' => $repair_id,
        'has_seen' => 1,
        'created_at' => $base->copy()->addHours(10),
        'updated_at' => $base->copy()->addHours(10),
    ],
    [
        'recipient' => $technician_user_id,
        'subject' => 'Repair Order Accepted!',
        'description' => 'Your repair order has been confirmed by ' . $user_name,
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id' => $repair_id,
        'has_seen' => 1,
        'created_at' => $base->copy()->addHours(14),
        'updated_at' => $base->copy()->addHours(14),
    ],
]);

for ($i = 0; $i < count($repair_progress_status); $i++) {
    DB::table('repair_progress')->insert([
        'repair_id' => $repair_id,
        'description' => $repair_progress_description[$i],
        'completion_rate' => $repair_progress_completion_rate[$i],
        'progress_status' => $repair_progress_status[$i],
        'created_at' => $base->copy()->addDays($repair_progress_days[$i]),
        'updated_at' => $base->copy()->addDays($repair_progress_days[$i]),
    ]);

    $subject = ($repair_progress_completion_rate[$i] == 100) ? 'Repair Completed!' : 'Repair Progress Added: ' . $repair_progress_status[$i];
    $desc = ($repair_progress_completion_rate[$i] == 100) ? 'Your repair has been completed.' : $repair_progress_description[$i];

    DB::table('notifications')->insert([
        'recipient' => $user_id,
        'subject' => $subject,
        'description' => $desc,
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id' => $repair_id,
        'has_seen' => 1,
        'created_at' => $base->copy()->addDays($repair_progress_days[$i]),
        'updated_at' => $base->copy()->addDays($repair_progress_days[$i]),
    ]);
}

// --------------------------------------------------------------------------------------------------------------------
// 5. FINAL MESSAGES & RATINGS
// --------------------------------------------------------------------------------------------------------------------
DB::table('conversation_messages')->insert([
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $technician_user_id,
        'message' => Crypt::encryptString('Hi, I have finished the repair, you may claim it now.'),
        'created_at' => $base->copy()->addDays($last_day)->addMinutes(1),
        'updated_at' => $base->copy()->addDays($last_day)->addMinutes(1),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $user_id,
        'message' => Crypt::encryptString('Brilliant! Thanks, ill claim later after work.'),
        'created_at' => $base->copy()->addDays($last_day)->addMinutes(4),
        'updated_at' => $base->copy()->addDays($last_day)->addMinutes(4),
    ],
]);

DB::table('repair_ratings')->insert([
    'repair_id' => $repair_id,
    'user_id' => $user_id,
    'technician_id' => $technician_id,
    'user_weighted_score' => 100.00,
    'user_comment' => 'The Technician did a fantastic job, no complaints here!',
    'technician_weighted_score' => 100.00,
    'technician_comment' => 'The client is easy to talk to and is direct.',
    'created_at' => $base->copy()->addDays($last_day)->addHours(5),
    'updated_at' => $base->copy()->addDays($last_day)->addHours(5),
]);

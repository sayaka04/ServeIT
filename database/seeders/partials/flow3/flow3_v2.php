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

$device = 'iPhone 13';
$device_type = 'Smartphone';
$issue_message = 'My iPhone keeps shutting down even when the battery is above 50%.';
$issues = '[{"issue":"Battery Issue"},{"issue":"Possible Power IC Problem"}]';
$breakdown = '[{"item":"Battery Replacement","price":1800},{"item":"Diagnostics + IC Repair","price":700}]';
$cost = 2500.00;

$repair_progress_status = [
    'Parts Have Arrived',
    'Repair Successfully Completed'
];

$repair_progress_description = [
    'Starting with the battery and power IC diagnostics.',
    'Battery replaced and IC recalibrated. Device is performing normally.'
];

$repair_progress_completion_rate = [40, 100];
$repair_progress_days = [1, 2];

$starting_week = -2;
$base = now()->addWeeks($starting_week);
$last_day = 2;

// --------------------------------------------------------------------------------------------------------------------
// 1. INITIAL INQUIRY & CHAT
// --------------------------------------------------------------------------------------------------------------------

DB::table('notifications')->insert([
    [
        'recipient' => $technician_user_id,
        'subject' => 'New Message from ' . $user_name,
        'description' => 'Inquiry regarding ' . $device,
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
        'repair_id' => null,
        'sender_id' => $user_id,
        'repair_accepted' => null,
        'message' => Crypt::encryptString('Good day!'),
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
        'created_at' => $base->copy()->addMinutes(1),
        'updated_at' => $base->copy()->addMinutes(1),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id' => null,
        'sender_id' => $technician_user_id,
        'repair_accepted' => null,
        'message' => Crypt::encryptString('Hello! What seems to be the problem with your device?'),
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
        'created_at' => $base->copy()->addMinutes(2),
        'updated_at' => $base->copy()->addMinutes(2),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id' => null,
        'sender_id' => $user_id,
        'repair_accepted' => null,
        'message' => Crypt::encryptString($issue_message),
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
        'created_at' => $base->copy()->addMinutes(3),
        'updated_at' => $base->copy()->addMinutes(3),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id' => null,
        'sender_id' => $technician_user_id,
        'repair_accepted' => null,
        'message' => Crypt::encryptString('Please bring the device over so I can check the battery health and power IC.'),
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
        'created_at' => $base->copy()->addMinutes(4),
        'updated_at' => $base->copy()->addMinutes(4),
    ],
]);

// --------------------------------------------------------------------------------------------------------------------
// 2. REPAIR INITIATION (Technician Creates the Record)
// --------------------------------------------------------------------------------------------------------------------

$repair_id = DB::table('repairs')->insertGetId([
    'conversation_id' => $conversation_id,
    'user_id' => $user_id,
    'technician_id' => $technician_id,
    'status' => 'completed',
    'device' => $device,
    'device_type' => $device_type,
    'receive_notes' => 'Device received. Physical condition is good.',
    'is_received' => 1,
    'is_completed' => 1,
    'completion_confirmed' => 1,
    'is_claimed' => 1,
    'issues' => $issues,
    'breakdown' => $breakdown,
    'estimated_cost' => $cost,
    'serial_number' => 'IP13CX00144729',
    'client_final_confirmation' => 1,
    'order_slip_path'           => 'repair-orders/repair_order_sample.pdf',
    'confirmation_signature_path' => 'signatures/sign_sample.jpeg',
    'claim_signature_path'      => 'signatures/sign_sample.jpeg',
    'completion_date' => $base->copy()->addDays(3),
    'created_at' => $base->copy()->addMinutes(6),
    'updated_at' => $base->copy()->addMinutes(6),
]);

// Blue Card sent by Technician
DB::table('conversation_messages')->insert([
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id' => $repair_id,
        'sender_id' => $technician_user_id, // Technician sends the card
        'repair_accepted' => null,
        'message' => Crypt::encryptString('*'),
        'image_name' => null,
        'image_type' => null,
        'image_path' => null,
        'file_name' => null,
        'file_type' => null,
        'file_path' => null,
        'url' => null,
        'url_name' => null,
        'url_domain' => null,
        'created_at' => $base->copy()->addMinutes(6),
        'updated_at' => $base->copy()->addMinutes(6),
    ],
]);

// --------------------------------------------------------------------------------------------------------------------
// 3. PROGRESS TRACKING & NOTIFICATIONS
// --------------------------------------------------------------------------------------------------------------------

DB::table('notifications')->insert([
    [
        'recipient' => $user_id,
        'subject' => 'Repair Breakdown Sent!',
        'description' => 'Your repair breakdown has been sent by ' . $technician_name,
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id' => $repair_id,
        'has_seen' => 1,
        'created_at' => $base->copy()->addHours(10),
        'updated_at' => $base->copy()->addHours(10),
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

    $notif_subject = ($repair_progress_completion_rate[$i] == 100)
        ? 'Your Device Repair is Completed!'
        : 'Repair Update: ' . $repair_progress_status[$i];

    DB::table('notifications')->insert([
        'recipient' => $user_id,
        'subject' => $notif_subject,
        'description' => $repair_progress_description[$i],
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id' => $repair_id,
        'has_seen' => 1,
        'created_at' => $base->copy()->addDays($repair_progress_days[$i]),
        'updated_at' => $base->copy()->addDays($repair_progress_days[$i]),
    ]);
}

// --------------------------------------------------------------------------------------------------------------------
// 4. PICKUP & RATINGS
// --------------------------------------------------------------------------------------------------------------------

DB::table('conversation_messages')->insert([
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $technician_user_id,
        'message' => Crypt::encryptString('Your iPhone is now fully repaired. You can pick it up anytime.'),
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
        'created_at' => $base->copy()->addDays($last_day)->addMinutes(1),
        'updated_at' => $base->copy()->addDays($last_day)->addMinutes(1),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $user_id,
        'message' => Crypt::encryptString('Great! I’ll drop by after work. Thanks again.'),
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
        'created_at' => $base->copy()->addDays($last_day)->addMinutes(4),
        'updated_at' => $base->copy()->addDays($last_day)->addMinutes(4),
    ],
]);

DB::table('repair_ratings')->insert([
    [
        'repair_id' => $repair_id,
        'user_id' => $user_id,
        'technician_id' => $technician_id,
        'user_weighted_score' => 98.00,
        'user_comment' => 'Great service and very fast. Highly recommended.',
        'technician_weighted_score' => 100.00,
        'technician_comment' => 'Client was clear in explaining the problem.',
        'created_at' => $base->copy()->addDays($last_day)->addHours(2),
        'updated_at' => $base->copy()->addDays($last_day)->addHours(2),
    ],
]);

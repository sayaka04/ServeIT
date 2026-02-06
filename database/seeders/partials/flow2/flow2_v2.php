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

$device = 'iPhone 13';
$device_type = 'Smartphone';
$issue_message = 'My iPhone keeps shutting down even when the battery is above 50%.';
$issues = '[{"issue":"Battery Issue"},{"issue":"Possible Power IC Problem"}]';
$breakdown = '[{"item":"Battery Replacement","price":1800},{"item":"Diagnostics + IC Repair","price":700}]';
$cost = 2500.00;

$starting_week = -2;
$base = now()->addWeeks($starting_week);
$last_day = 2;

// --------------------------------------------------------------------------------------------------------------------
// 1. CONVERSATION FETCHING
// --------------------------------------------------------------------------------------------------------------------
$conversation_id = DB::table('conversations')->where('conversation_code', $conversation_code)->value('id');

// --------------------------------------------------------------------------------------------------------------------
// 2. INQUIRY PHASE (Digital Chat Only)
// --------------------------------------------------------------------------------------------------------------------

// Initial Notification for the Technician
DB::table('notifications')->insert([
    'recipient'       => $technician_user_id,
    'subject'         => 'New Message from ' . $user_name,
    'description'     => 'A client is inquiring about a ' . $device_type,
    'notifiable_type' => 'App\Models\Conversation',
    'notifiable_id'   => $conversation_id,
    'has_seen'        => 1,
    'created_at'      => $base->copy()->addMinutes(1),
    'updated_at'      => $base->copy()->addMinutes(1),
]);

DB::table('conversation_messages')->insert([
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $user_id,
        'message'            => Crypt::encryptString('Good day!'),
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
        'message'            => Crypt::encryptString('Hello! What seems to be the problem with your device?'),
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
        'message'            => Crypt::encryptString('Please bring the device to the shop so I can check it.'),
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
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $user_id,
        'message'            => Crypt::encryptString('Sure thing. I’ll head there later today.'),
        'created_at'         => $base->copy()->addMinutes(8),
        'updated_at'         => $base->copy()->addMinutes(8),
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

// *** TIME JUMP: Arrival at Shop (2 Hours Later) ***
$arrival_time = $base->copy()->addHours(2);

// --------------------------------------------------------------------------------------------------------------------
// 3. REPAIR RECORD CREATION (Physical Intake)
// --------------------------------------------------------------------------------------------------------------------
$repair_id = DB::table('repairs')->insertGetId([
    'conversation_id'           => $conversation_id,
    'user_id'                   => $user_id,
    'technician_id'             => $technician_id,
    'status'                    => 'completed',
    'device'                    => $device,
    'device_type'               => $device_type,
    'receive_notes'             => 'Device received in good condition. Minor scratches but nothing critical.',
    'is_received'               => 1,
    'is_completed'              => 1,
    'completion_confirmed'      => 1,
    'is_claimed'                => 1,
    'issues'                    => $issues,
    'breakdown'                 => $breakdown,
    'estimated_cost'            => $cost,
    'serial_number'             => 'IP13CX00144729',
    'client_final_confirmation' => 1,
    'order_slip_path'           => 'repair-orders/repair_order_sample.pdf',
    'confirmation_signature_path' => 'signatures/sign_sample.jpeg',
    'claim_signature_path'      => 'signatures/sign_sample.jpeg',
    'completion_date'           => $arrival_time->copy()->addDays(2),
    'created_at'                => $arrival_time,
    'updated_at'                => $arrival_time,
]);

// Blue Card System Message (Technician sends this trigger)
DB::table('conversation_messages')->insert([
    'conversation_id'    => $conversation_id,
    'user_id'            => $user_id,
    'technician_user_id' => $technician_user_id,
    'repair_id'          => $repair_id,
    'sender_id'          => $technician_user_id,
    'message'            => Crypt::encryptString('*'),
    'created_at'         => $arrival_time->copy()->addMinutes(1),
    'updated_at'         => $arrival_time->copy()->addMinutes(1),
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
]);

// Breakdown Notification
DB::table('notifications')->insert([
    'recipient'       => $user_id,
    'subject'         => 'Breakdown for your repair sent!',
    'description'     => 'Your repair breakdown has been sent by ' . $technician_name,
    'notifiable_type' => 'App\Models\Repair',
    'notifiable_id'   => $repair_id,
    'has_seen'        => 1,
    'created_at'      => $arrival_time->copy()->addMinutes(2),
    'updated_at'      => $arrival_time->copy()->addMinutes(2),
]);

// --------------------------------------------------------------------------------------------------------------------
// 4. PROGRESS UPDATES
// --------------------------------------------------------------------------------------------------------------------
$repair_progress_status = ['Parts Have Arrived', 'Repair Successfully Completed'];
$repair_progress_description = [
    'Starting with the battery and power IC diagnostics.',
    'Battery replaced and IC recalibrated. Device is performing normally.'
];
$repair_progress_rates = [40, 100];
$repair_progress_days = [1, 2];

for ($i = 0; $i < count($repair_progress_status); $i++) {
    $p_time = $arrival_time->copy()->addDays($repair_progress_days[$i]);

    DB::table('repair_progress')->insert([
        'repair_id'       => $repair_id,
        'description'     => $repair_progress_description[$i],
        'completion_rate' => $repair_progress_rates[$i],
        'progress_status' => $repair_progress_status[$i],
        'created_at'      => $p_time,
        'updated_at'      => $p_time,
    ]);

    DB::table('notifications')->insert([
        'recipient'       => $user_id,
        'subject'         => ($repair_progress_rates[$i] == 100) ? 'Repair Completed!' : 'Repair Update: ' . $repair_progress_status[$i],
        'description'     => $repair_progress_description[$i],
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id'   => $repair_id,
        'has_seen'        => 1,
        'created_at'      => $p_time,
        'updated_at'      => $p_time,
    ]);
}

// --------------------------------------------------------------------------------------------------------------------
// 5. FINAL MESSAGES & RATING
// --------------------------------------------------------------------------------------------------------------------
$final_time = $arrival_time->copy()->addDays($last_day)->addHours(2);

DB::table('conversation_messages')->insert([
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $technician_user_id,
        'message'            => Crypt::encryptString('Your iPhone is now fully repaired. You can pick it up anytime.'),
        'created_at'         => $final_time->copy()->addMinutes(1),
        'updated_at'         => $final_time->copy()->addMinutes(1),
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
        'message'            => Crypt::encryptString('Great! I’ll drop by after work. Thanks again.'),
        'created_at'         => $final_time->copy()->addMinutes(4),
        'updated_at'         => $final_time->copy()->addMinutes(4),
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

DB::table('repair_ratings')->insert([
    [
        'repair_id'                 => $repair_id,
        'user_id'                   => $user_id,
        'technician_id'             => $technician_id,
        'user_weighted_score'       => 98.00,
        'user_comment'              => 'Great service and very fast. Highly recommended.',
        'technician_weighted_score' => 100.00,
        'technician_comment'        => 'Client was clear in explaining the problem.',
        'created_at'                => $final_time->copy()->addHours(5),
        'updated_at'                => $final_time->copy()->addHours(5),
    ],
]);

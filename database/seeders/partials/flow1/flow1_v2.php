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

$device = 'iPhone 13';
$device_type = 'Smartphone';
$issue_message = 'My iPhone keeps shutting down even when the battery is above 50%.';
$issues = '[{"issue":"Battery Issue"},{"issue":"Possible Power IC Problem"}]';
$breakdown = '[{"item":"Battery Replacement","price":1800},{"item":"Diagnostics + IC Repair","price":700}]';
$cost = 2500.00;

// TIMELINE SETUP
// Previous repair was -1 (1 week ago). We set this to 0 (Current Week) to make it the "latest".
$starting_week = -2;
$base = now()->addWeeks($starting_week);

// --------------------------------------------------------------------------------------------------------------------
// 1. CONVERSATION CREATION
// --------------------------------------------------------------------------------------------------------------------
// $conversation_id = DB::table('conversations')->insertGetId([
//     'conversation_code' => $conversation_code,
//     'user_id' => $user_id,
//     'technician_user_id' => $technician_user_id,
//     'technician_id' => $technician_id,
//     'topic' => null,
//     'c_last_seen' => now(),
//     't_last_seen' => now(),
//     'c_is_notified' => 0,
//     't_is_notified' => 0,
//     'created_at' => $base->copy(),
//     'updated_at' => $base->copy(),
// ]);

// --------------------------------------------------------------------------------------------------------------------
// 2. INQUIRY PHASE (Chatting Online)
// --------------------------------------------------------------------------------------------------------------------

// Client: Good day
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $user_id,
    'message'              => Crypt::encryptString('Good day!'),
    'created_at'           => $base->copy()->addMinutes(1),
    'updated_at'           => $base->copy()->addMinutes(1),
]);

// Tech: Hello
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $technician_user_id,
    'message'              => Crypt::encryptString('Hello! What seems to be the problem with your device?'),
    'created_at'           => $base->copy()->addMinutes(2),
    'updated_at'           => $base->copy()->addMinutes(2),
]);

// Client: Explains Issue
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $user_id,
    'message'              => Crypt::encryptString($issue_message),
    'created_at'           => $base->copy()->addMinutes(3),
    'updated_at'           => $base->copy()->addMinutes(3),
]);

// Tech: Asks for model
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $technician_user_id,
    'message'              => Crypt::encryptString('Can you tell me the device model and its current condition?'),
    'created_at'           => $base->copy()->addMinutes(4),
    'updated_at'           => $base->copy()->addMinutes(4),
]);

// Client: iPhone 13
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $user_id,
    'message'              => Crypt::encryptString($device),
    'created_at'           => $base->copy()->addMinutes(5),
    'updated_at'           => $base->copy()->addMinutes(5),
]);

// --------------------------------------------------------------------------------------------------------------------
// 3. NEGOTIATION (Before Repair Creation)
// --------------------------------------------------------------------------------------------------------------------

// Tech: Bring it in
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $technician_user_id,
    'message'              => Crypt::encryptString('Please bring the device to the shop so I can check the battery health and IC.'),
    'created_at'           => $base->copy()->addMinutes(7),
    'updated_at'           => $base->copy()->addMinutes(7),
]);

// Client: Agrees to come (This MUST happen before the repair row is created)
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $user_id,
    'message'              => Crypt::encryptString('Approved. I’ll head there later today.'),
    'created_at'           => $base->copy()->addMinutes(8),
    'updated_at'           => $base->copy()->addMinutes(8),
]);

// Tech: Waiting
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $technician_user_id,
    'message'              => Crypt::encryptString('Alright! I’ll wait for you.'),
    'created_at'           => $base->copy()->addMinutes(9),
    'updated_at'           => $base->copy()->addMinutes(9),
]);

// *** TIME JUMP: 2 HOURS LATER (Client Travels to Shop) ***
$arrival_time = $base->copy()->addHours(2);

// --------------------------------------------------------------------------------------------------------------------
// 4. REPAIR CREATION (Technician Physically Receives Device)
// --------------------------------------------------------------------------------------------------------------------

$repair_id = DB::table('repairs')->insertGetId([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_id'        => $technician_id,
    'status'               => 'completed',
    'device'               => $device,
    'device_type'          => $device_type,
    'receive_notes'        => 'Device received in good condition. Minor scratches but nothing critical.',
    'is_received'          => 1,
    'is_completed'         => 1,
    'completion_confirmed' => 1,
    'is_claimed'           => 1,

    // Sample Files Reused as requested
    'order_slip_path' => 'repair-orders/repair_order_sample.pdf',
    'confirmation_signature_path' => 'signatures/sign_sample.jpeg',
    'claim_signature_path' => 'signatures/sign_sample.jpeg',

    'issues'               => $issues,
    'breakdown'            => $breakdown,
    'estimated_cost'       => $cost,
    'serial_number'        => 'IP13CX00144729',
    'client_final_confirmation' => 1,
    'completion_date'      => $arrival_time->copy()->addDays(3),
    'created_at'           => $arrival_time, // Created AFTER arrival
    'updated_at'           => $arrival_time,
]);

// --------------------------------------------------------------------------------------------------------------------
// 5. SYSTEM MESSAGES (The Blue Card)
// --------------------------------------------------------------------------------------------------------------------

// Blue Card System Message
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'repair_id'            => $repair_id,
    'sender_id'            => $technician_user_id,
    'message'              => Crypt::encryptString('*'),
    'created_at'           => $arrival_time->copy()->addMinutes(5),
    'updated_at'           => $arrival_time->copy()->addMinutes(5),
]);

// Notification: Repair Created/Accepted
DB::table('notifications')->insert([
    'recipient'            => $technician_user_id,
    'subject'              => 'New repair accepted!',
    'description'          => 'Your repair request has been accepted by ' . $user_name,
    'notifiable_type'      => 'App\Models\Repair',
    'notifiable_id'        => $repair_id,
    'has_seen'             => 1,
    'created_at'           => $arrival_time->copy()->addMinutes(6),
    'updated_at'           => $arrival_time->copy()->addMinutes(6),
]);

// --------------------------------------------------------------------------------------------------------------------
// 6. PROGRESS UPDATES
// --------------------------------------------------------------------------------------------------------------------

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

// Notify breakdown sent
DB::table('notifications')->insert([
    'recipient'            => $user_id,
    'subject'              => 'Repair Breakdown Sent!',
    'description'          => 'Your repair breakdown has been sent by ' . $technician_name,
    'notifiable_type'      => 'App\Models\Repair',
    'notifiable_id'        => $repair_id,
    'has_seen'             => 1,
    'created_at'           => $arrival_time->copy()->addMinutes(10), // Shortly after creation
    'updated_at'           => $arrival_time->copy()->addMinutes(10),
]);

// Loop updates
for ($i = 0; $i < count($repair_progress_status); $i++) {
    $progress_time = $arrival_time->copy()->addDays($repair_progress_days[$i]);

    DB::table('repair_progress')->insert([
        'repair_id'       => $repair_id,
        'description'     => $repair_progress_description[$i],
        'completion_rate' => $repair_progress_completion_rate[$i],
        'progress_status' => $repair_progress_status[$i],
        'created_at'      => $progress_time,
        'updated_at'      => $progress_time,
    ]);

    // Client Notification
    DB::table('notifications')->insert([
        'recipient'       => $user_id,
        'subject'         => ($repair_progress_completion_rate[$i] == 100) ? 'Your Device Repair is Completed!' : 'Repair Update: ' . $repair_progress_status[$i],
        'description'     => ($repair_progress_completion_rate[$i] == 100) ? 'Your device is ready for claiming.' : $repair_progress_description[$i],
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id'   => $repair_id,
        'has_seen'        => 1,
        'created_at'      => $progress_time,
        'updated_at'      => $progress_time,
    ]);
}

// --------------------------------------------------------------------------------------------------------------------
// 7. CLAIMING & CLOSING
// --------------------------------------------------------------------------------------------------------------------

$completion_time = $arrival_time->copy()->addDays(2)->addHours(2);

// Tech: Ready for pickup
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $technician_user_id,
    'message'              => Crypt::encryptString('Your iPhone is now fully repaired. You can pick it up anytime.'),
    'created_at'           => $completion_time,
    'updated_at'           => $completion_time,
]);

// Client: Coming
DB::table('conversation_messages')->insert([
    'conversation_id'      => $conversation_id,
    'user_id'              => $user_id,
    'technician_user_id'   => $technician_user_id,
    'sender_id'            => $user_id,
    'message'              => Crypt::encryptString('Great! I’ll drop by after work. Thanks again.'),
    'created_at'           => $completion_time->copy()->addMinutes(10),
    'updated_at'           => $completion_time->copy()->addMinutes(10),
]);

// --------------------------------------------------------------------------------------------------------------------
// 8. FINAL RATINGS (After Claiming)
// --------------------------------------------------------------------------------------------------------------------

DB::table('repair_ratings')->insert([
    'repair_id'                 => $repair_id,
    'user_id'                   => $user_id,
    'technician_id'             => $technician_id,
    'user_weighted_score'       => 98.00,
    'user_comment'              => 'Great service and very fast. Highly recommended.',
    'technician_weighted_score' => 100.00,
    'technician_comment'        => 'Client was clear in explaining the problem.',
    'created_at'                => $completion_time->copy()->addHours(4),
    'updated_at'                => $completion_time->copy()->addHours(4),
]);

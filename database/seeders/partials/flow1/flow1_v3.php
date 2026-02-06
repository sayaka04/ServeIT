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

$device = 'Dell Inspiron 16';
$device_type = 'Laptop';
$issue_message = 'Laptop randomly shuts down even when battery is full, sometimes makes a beeping sound.';
$issues = '[{"issue":"Power Supply Problem"},{"issue":"Motherboard Issue"}]';
$breakdown = '[{"item":"Power Module Replacement","price":1500},{"item":"Diagnostics & Repair Fee","price":600}]';
$cost = 2100.00;

$starting_week = -1;
$base = now()->addWeeks($starting_week);
$last_day = 2;

// --------------------------------------------------------------------------------------------------------------------
// 1. CONVERSATION CREATION
// --------------------------------------------------------------------------------------------------------------------
// $conversation_id = DB::table('conversations')->insertGetId([
//     'conversation_code' => $conversation_code,
//     'user_id' => $user_id,
//     'technician_user_id' => $technician_user_id,
//     'technician_id' => $technician_id,
//     'topic' => null,
//     'c_last_seen' => $base->copy()->addHours(1),
//     't_last_seen' => $base->copy()->addHours(1),
//     'c_is_notified' => 0,
//     't_is_notified' => 0,
//     'created_at' => $base->copy(),
//     'updated_at' => $base->copy(),
// ]);

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
        'message'            => Crypt::encryptString('Hi, hope you’re doing well.'),
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
        'message'            => Crypt::encryptString('Hi! What seems to be the problem with your laptop?'),
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
        'message'            => Crypt::encryptString('Okay, I will be there in a while.'),
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
]);

// *** TIME JUMP: Arrival at Shop (2 Hours Later) ***
$arrival_time = $base->copy()->addHours(2);

// --------------------------------------------------------------------------------------------------------------------
// 3. REPAIR INITIATION (Physically Received)
// --------------------------------------------------------------------------------------------------------------------
$repair_id = DB::table('repairs')->insertGetId([
    'conversation_id'           => $conversation_id,
    'user_id'                   => $user_id,
    'technician_id'             => $technician_id,
    'status'                    => 'completed',
    'device'                    => $device,
    'device_type'               => $device_type,
    'receive_notes'             => 'Device received without major damages. Some dust inside, but functional.',
    'is_received'               => 1,
    'is_completed'              => 1,
    'completion_confirmed'      => 1,
    'is_claimed'                => 1,
    'issues'                    => $issues,
    'breakdown'                 => $breakdown,
    'estimated_cost'            => $cost,
    'serial_number'             => 'DELL16X00012345',
    'client_final_confirmation' => 1,
    'order_slip_path'           => 'repair-orders/repair_order_sample.pdf',
    'confirmation_signature_path' => 'signatures/sign_sample.jpeg',
    'claim_signature_path'      => 'signatures/sign_sample.jpeg',
    'completion_date'           => $arrival_time->copy()->addDays(2),
    'created_at'                => $arrival_time,
    'updated_at'                => $arrival_time,
]);

// 4. THE REPAIR CARD (*) - Sent after shop intake
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

DB::table('notifications')->insert([
    [
        'recipient'       => $user_id,
        'subject'         => 'Breakdown for your repair sent!',
        'description'     => 'Your repair breakdown has been sent by ' . $technician_name,
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id'   => $repair_id,
        'has_seen'        => 1,
        'created_at'      => $arrival_time->copy()->addMinutes(6),
        'updated_at'      => $arrival_time->copy()->addMinutes(6),
    ],
]);

// --------------------------------------------------------------------------------------------------------------------
// 5. PROGRESS UPDATES
// --------------------------------------------------------------------------------------------------------------------
$repair_progress_status = ['Replacement Parts Arrived', 'Repair Finished Successfully'];
$repair_progress_description = ['Power module replaced and motherboard tested.', 'All repairs complete. Laptop is now stable and functioning normally.'];
$rates = [45, 100];
$days = [1, 2];

for ($i = 0; $i < count($repair_progress_status); $i++) {
    $p_time = $arrival_time->copy()->addDays($days[$i]);

    DB::table('repair_progress')->insert([
        'repair_id'       => $repair_id,
        'description'     => $repair_progress_description[$i],
        'completion_rate' => $rates[$i],
        'progress_status' => $repair_progress_status[$i],
        'created_at'      => $p_time,
        'updated_at'      => $p_time,
    ]);

    DB::table('notifications')->insert([
        'recipient'       => $user_id,
        'subject'         => ($rates[$i] == 100) ? 'Repair Completed!' : 'Repair Update: ' . $repair_progress_status[$i],
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
$final_time = $arrival_time->copy()->addDays(2)->addHours(2);

DB::table('conversation_messages')->insert([
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id'          => $technician_user_id,
        'message'            => Crypt::encryptString('Laptop repair is done. You can claim it at your convenience.'),
        'created_at'         => $final_time,
        'updated_at'         => $final_time,
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
        'message'            => Crypt::encryptString('Thank you, I will pick it up after work.'),
        'created_at'         => $final_time->copy()->addMinutes(10),
        'updated_at'         => $final_time->copy()->addMinutes(10),
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
        'user_weighted_score'       => 99.00,
        'user_comment'              => 'Quick and efficient. Device works perfectly now.',
        'technician_weighted_score' => 100.00,
        'technician_comment'        => 'Client explained the issues clearly, very cooperative.',
        'created_at'                => $final_time->copy()->addHours(4),
        'updated_at'                => $final_time->copy()->addHours(4),
    ],
]);

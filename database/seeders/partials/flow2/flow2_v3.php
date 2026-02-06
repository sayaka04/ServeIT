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
// 1. CONVERSATION FETCHING
// --------------------------------------------------------------------------------------------------------------------
$conversation_id = DB::table('conversations')->where('conversation_code', $conversation_code)->value('id');

// --------------------------------------------------------------------------------------------------------------------
// 2. INQUIRY PHASE (Digital Chat Only)
// --------------------------------------------------------------------------------------------------------------------

// Notify Technician
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
        'message'            => Crypt::encryptString('Okay. I will come by tomorrow morning.'),
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

// *** LOGISTICAL GAP: Physical Drop-off ***
$drop_off_time = $base->copy()->addDays(1)->setHour(10)->setMinute(0);

// --------------------------------------------------------------------------------------------------------------------
// 3. REPAIR RECORD & BLUE CARD
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
    'completion_date'           => $drop_off_time->copy()->addDays(2),
    'created_at'                => $drop_off_time,
    'updated_at'                => $drop_off_time,
]);

// Blue Card (Sent by Technician Jeson)
DB::table('conversation_messages')->insert([
    'conversation_id'    => $conversation_id,
    'user_id'            => $user_id,
    'technician_user_id' => $technician_user_id,
    'repair_id'          => $repair_id,
    'sender_id'          => $technician_user_id,
    'message'            => Crypt::encryptString('*'),
    'created_at'         => $drop_off_time->copy()->addMinutes(1),
    'updated_at'         => $drop_off_time->copy()->addMinutes(1),
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

// --------------------------------------------------------------------------------------------------------------------
// 4. PROGRESS UPDATES
// --------------------------------------------------------------------------------------------------------------------
$progress_data = [
    ['status' => 'Replacement Parts Arrived', 'desc' => 'Power module replaced and motherboard tested.', 'rate' => 45, 'delay' => 1],
    ['status' => 'Repair Finished Successfully', 'desc' => 'All repairs complete. Laptop is now stable.', 'rate' => 100, 'delay' => 2],
];

foreach ($progress_data as $data) {
    $p_time = $drop_off_time->copy()->addDays($data['delay']);
    DB::table('repair_progress')->insert([
        'repair_id'       => $repair_id,
        'description'     => $data['desc'],
        'completion_rate' => $data['rate'],
        'progress_status' => $data['status'],
        'created_at'      => $p_time,
        'updated_at'      => $p_time,
    ]);

    DB::table('notifications')->insert([
        'recipient'       => $user_id,
        'subject'         => ($data['rate'] == 100) ? 'Repair Completed!' : 'Repair Update: ' . $data['status'],
        'description'     => $data['desc'],
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id'   => $repair_id,
        'has_seen'        => 1,
        'created_at'      => $p_time,
        'updated_at'      => $p_time,
    ]);
}

// --------------------------------------------------------------------------------------------------------------------
// 5. FINAL HANDOVER & RATING
// --------------------------------------------------------------------------------------------------------------------
$final_time = $drop_off_time->copy()->addDays($last_day)->addHours(2);

DB::table('conversation_messages')->insert([
    [
        'conversation_id'    => $conversation_id,
        'user_id'            => $user_id,
        'technician_user_id' => $technician_user_id,
        'repair_id'          => null,
        'sender_id'          => $technician_user_id,
        'message'            => Crypt::encryptString('Laptop repair is done. You can claim it at your convenience.'),
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
        'message'            => Crypt::encryptString('Thank you, I will pick it up after work.'),
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
    'repair_id'                 => $repair_id,
    'user_id'                   => $user_id,
    'technician_id'             => $technician_id,
    'user_weighted_score'       => 99.00,
    'user_comment'              => 'Quick and efficient. Device works perfectly now.',
    'technician_weighted_score' => 100.00,
    'technician_comment'        => 'Client explained the issues clearly, very cooperative.',
    'created_at'                => $final_time->copy()->addHours(2),
    'updated_at'                => $final_time->copy()->addHours(2),
]);

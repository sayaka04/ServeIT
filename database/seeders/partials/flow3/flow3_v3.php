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

$device = 'Dell Inspiron 16';
$device_type = 'Laptop';
$issue_message = 'Laptop randomly shuts down even when battery is full, sometimes makes a beeping sound.';
$issues = '[{"issue":"Power Supply Problem"},{"issue":"Motherboard Issue"}]';
$breakdown = '[{"item":"Power Module Replacement","price":1500},{"item":"Diagnostics & Repair Fee","price":600}]';
$cost = 2100.00;

$repair_progress_status = [
    'Replacement Parts Arrived',
    'Repair Finished Successfully'
];

$repair_progress_description = [
    'Power module replaced and motherboard tested.',
    'All repairs complete. Laptop is now stable and functioning normally.'
];

$repair_progress_completion_rate = [45, 100];
$repair_progress_days = [1, 2];

$starting_week = -1;
$base = now()->addWeeks($starting_week);
$last_day = 2;

// --------------------------------------------------------------------------------------------------------------------
// 1. INQUIRY PHASE
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
        'sender_id' => $user_id,
        'message' => Crypt::encryptString('Hi again, hope you’re doing well.'),
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
        'created_at' => $base->copy()->addMinutes(1),
        'updated_at' => $base->copy()->addMinutes(1),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $technician_user_id,
        'message' => Crypt::encryptString('Hi! What seems to be the problem with your laptop?'),
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
        'created_at' => $base->copy()->addMinutes(2),
        'updated_at' => $base->copy()->addMinutes(2),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $user_id,
        'message' => Crypt::encryptString($issue_message),
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
        'created_at' => $base->copy()->addMinutes(3),
        'updated_at' => $base->copy()->addMinutes(3),
    ],
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $technician_user_id,
        'message' => Crypt::encryptString('The beeping usually indicates a motherboard or power fault. Bring it over for a full check.'),
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
        'created_at' => $base->copy()->addMinutes(4),
        'updated_at' => $base->copy()->addMinutes(4),
    ],
]);

// --------------------------------------------------------------------------------------------------------------------
// 2. REPAIR INITIATION (Technician Creates Record)
// --------------------------------------------------------------------------------------------------------------------

$repair_id = DB::table('repairs')->insertGetId([
    'conversation_id' => $conversation_id,
    'user_id' => $user_id,
    'technician_id' => $technician_id,
    'status' => 'completed',
    'device' => $device,
    'device_type' => $device_type,
    'receive_notes' => 'Device received without major damages. Some dust inside, but functional.',
    'is_received' => 1,
    'is_completed' => 1,
    'completion_confirmed' => 1,
    'is_claimed' => 1,
    'issues' => $issues,
    'breakdown' => $breakdown,
    'estimated_cost' => $cost,
    'serial_number' => 'DELL16X00012345',
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
    'conversation_id' => $conversation_id,
    'user_id' => $user_id,
    'technician_user_id' => $technician_user_id,
    'repair_id' => $repair_id,
    'sender_id' => $technician_user_id,
    'message' => Crypt::encryptString('*'),
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
    'created_at' => $base->copy()->addMinutes(6),
    'updated_at' => $base->copy()->addMinutes(6),
]);

// --------------------------------------------------------------------------------------------------------------------
// 3. PROGRESS TRACKING & NOTIFICATIONS
// --------------------------------------------------------------------------------------------------------------------

DB::table('notifications')->insert([
    [
        'recipient' => $user_id,
        'subject' => 'Repair Breakdown Sent!',
        'description' => 'Repair breakdown has been sent by ' . $technician_name,
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id' => $repair_id,
        'has_seen' => 1,
        'created_at' => $base->copy()->addHours(10),
        'updated_at' => $base->copy()->addHours(10),
    ],
    [
        'recipient' => $technician_user_id,
        'subject' => 'Repair Order Confirmed!',
        'description' => 'Repair order confirmed by ' . $user_name . '. PDF attached.',
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

    $subj = ($repair_progress_completion_rate[$i] == 100) ? 'Your Device Repair is Completed!' : 'Repair Update: ' . $repair_progress_status[$i];
    $desc = ($repair_progress_completion_rate[$i] == 100) ? 'Your laptop is ready for claiming.' : $repair_progress_description[$i];

    DB::table('notifications')->insert([
        'recipient' => $user_id,
        'subject' => $subj,
        'description' => $desc,
        'notifiable_type' => 'App\Models\Repair',
        'notifiable_id' => $repair_id,
        'has_seen' => 1,
        'created_at' => $base->copy()->addDays($repair_progress_days[$i]),
        'updated_at' => $base->copy()->addDays($repair_progress_days[$i]),
    ]);
}

// --------------------------------------------------------------------------------------------------------------------
// 4. FINAL PICKUP & RATINGS
// --------------------------------------------------------------------------------------------------------------------

DB::table('conversation_messages')->insert([
    [
        'conversation_id' => $conversation_id,
        'user_id' => $user_id,
        'technician_user_id' => $technician_user_id,
        'sender_id' => $technician_user_id,
        'message' => Crypt::encryptString('Laptop repair is done. You can claim it at your convenience.'),
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
        'message' => Crypt::encryptString('Thank you, I will pick it up after work.'),
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
    'repair_id' => $repair_id,
    'user_id' => $user_id,
    'technician_id' => $technician_id,
    'user_weighted_score' => 99.00,
    'user_comment' => 'Quick and efficient. Device works perfectly now.',
    'technician_weighted_score' => 100.00,
    'technician_comment' => 'Client explained the issues clearly, very cooperative.',
    'created_at' => $base->copy()->addDays($last_day)->addHours(2),
    'updated_at' => $base->copy()->addDays($last_day)->addHours(2),
]);

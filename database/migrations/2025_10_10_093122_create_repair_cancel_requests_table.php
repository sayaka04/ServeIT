<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_cancel_requests', function (Blueprint $table) {
            $table->id();

            // The repair that is being requested for cancellation
            $table->foreignId('repair_id')->constrained('repairs')->onDelete('cascade');

            // The person who made the cancel request (user or technician)
            $table->foreignId('requestor_id')->constrained('users')->onDelete('cascade');

            // The person who reviews or approves the request
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null');

            // The reason for the cancellation
            $table->text('reason');

            // Whether the request has been accepted or rejected (null = pending)
            $table->boolean('is_accepted')->nullable();

            // Default timestamps
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_cancel_requests');
    }
};

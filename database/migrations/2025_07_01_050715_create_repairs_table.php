<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id(); // Primary Key
            // Foreign key to the user who initiated the repair
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Foreign key to the technician assigned to the repair
            $table->foreignId('technician_id')->nullable()->constrained('technicians')->onDelete('set null'); // Technician might be unassigned or deleted
            // Foreign key to the related conversation
            $table->foreignId('conversation_id')->nullable()->constrained('conversations')->onDelete('set null'); // Conversation might be optional or deleted

            //--------------------------------------------------------------------------------------
            //The changes in this major changing below: 3 columns
            $table->json('issues')->nullable();       // NEW: JSON array of multiple issues
            $table->json('breakdown')->nullable();    // NEW: JSON for breakdown info
            $table->string('serial_number', 45)->nullable();
            $table->boolean('client_final_confirmation')->nullable();
            $table->string('order_slip_path')->nullable();
            //--------------------------------------------------------------------------------------

            $table->string('confirmation_signature_path')->nullable();
            $table->timestamp('confirmation_date')->nullable();

            $table->string('claim_signature_path')->nullable();
            $table->timestamp('claim_date')->nullable();


            $table->text('disclaimer')->nullable();
            $table->string('device')->nullable(); // e.g., 'Laptop', 'Phone'
            $table->string('device_type')->nullable(); // e.g., 'Apple MacBook Pro', 'Samsung Galaxy S22'
            $table->string('status')->default('pending'); // e.g., 'pending', 'in_progress', 'completed', 'cancelled'
            $table->decimal('estimated_cost', 8, 2)->nullable(); // Add a decimal column for repair cost (8 digits, 2 decimal points)
            $table->boolean('is_cancelled')->default(false);
            $table->boolean('is_completed')->default(false);

            $table->boolean('is_received')->nullable();
            $table->string('receive_file_path')->nullable();
            $table->text('receive_notes')->nullable();

            $table->boolean('is_claimed')->nullable();
            // $table->string('claim_file_path')->nullable();
            // $table->text('claim_notes')->nullable();

            $table->boolean('completion_confirmed')->nullable();
            $table->timestamp('completion_date')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};

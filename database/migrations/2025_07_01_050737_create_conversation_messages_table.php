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
        Schema::create('conversation_messages', function (Blueprint $table) {
            $table->id(); // Primary Key
            // Foreign key to the conversation this message belongs to
            $table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            // Foreign key to the user who sent the message
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('technician_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('repair_id')->nullable()->constrained('repairs')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->boolean('repair_accepted')->nullable();
            $table->text('message');
            $table->string('image_name')->nullable();
            $table->string('image_type')->nullable();
            $table->string('image_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_path')->nullable();
            $table->string('url')->nullable();
            $table->string('url_name')->nullable();
            $table->string('url_domain')->nullable();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_messages');
    }
};

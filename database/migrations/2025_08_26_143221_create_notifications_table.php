<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recipient')->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->text('description')->nullable();

            // Polymorphic relationship fields
            $table->morphs('notifiable'); // Creates notifiable_type and notifiable_id

            $table->boolean('has_seen')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repair_ratings', function (Blueprint $table) {
            $table->id(); // Primary Key
            // Foreign key to the user who gave the rating
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Foreign key to the technician who received the rating
            $table->foreignId('technician_id')->constrained('technicians')->onDelete('cascade');
            // Foreign key to the repair that was rated
            $table->foreignId('repair_id')->unique()->constrained('repairs')->onDelete('cascade'); // Assuming one rating per repair
            $table->decimal('user_weighted_score', 5, 2)->nullable(); // Rating given by the user
            $table->decimal('technician_weighted_score', 5, 2)->nullable(); // Rating given by the technician (if applicable)
            $table->text('user_comment')->nullable(); // User's comment, NOT nullable
            $table->text('technician_comment')->nullable(); // Technician's comment, NOT nullable
            $table->timestamps(); // Created at and Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_ratings');
    }
};

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
        Schema::create('repair_progress', function (Blueprint $table) {
            $table->id(); // Primary Key
            // Foreign key to the repair this progress update belongs to
            $table->foreignId('repair_id')->constrained('repairs')->onDelete('cascade');
            $table->text('description')->nullable(); // Description of the progress update
            $table->integer('completion_rate')->nullable(); // Percentage, e.g., 0-100
            $table->string('progress_status')->nullable(); // e.g., 'started', 'parts_ordered', 'testing', 'completed'
            $table->text('progress_file_path')->nullable(); // Path to the progress file
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_progress');
    }
};

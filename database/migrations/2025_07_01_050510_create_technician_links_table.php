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
        Schema::create('technician_links', function (Blueprint $table) {
            $table->id(); // Primary Key
            // Foreign key to the technicians table
            $table->foreignId('technician_id')->constrained('technicians')->onDelete('cascade');
            $table->string('url');
            $table->string('type')->nullable(); // e.g., 'website', 'portfolio', 'social_media'
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technician_links');
    }
};

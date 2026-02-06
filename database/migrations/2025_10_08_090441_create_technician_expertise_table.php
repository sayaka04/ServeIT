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
        // Pivot table name is typically singular of both related models, separated by an underscore
        Schema::create('technician_expertise', function (Blueprint $table) {

            // Primary Key (Optional but good for tracking)
            $table->id();

            // Foreign key to the technicians table. 
            // If a technician is deleted, their expertise records are removed (cascade).
            $table->foreignId('technician_id')
                ->constrained('technicians')
                ->onDelete('cascade');

            // Foreign key to the expertise_categories table.
            // If an expertise category is deleted, records linking to it are removed (cascade).
            $table->foreignId('expertise_category_id')
                ->constrained('expertise_categories')
                ->onDelete('cascade');

            // Enforce that a technician can only be linked to an expertise category once.
            $table->unique(['technician_id', 'expertise_category_id']);
            $table->boolean('is_archived')->default(false);


            // Standard timestamps
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technician_expertise');
    }
};

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
        Schema::create('expertise_categories', function (Blueprint $table) {
            // Primary key for the category
            $table->id();

            // The name of the expertise (must be unique)
            $table->string('name', 100)->unique();

            // A brief description of the expertise area
            $table->text('description')->nullable();
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
        Schema::dropIfExists('expertise_categories');
    }
};

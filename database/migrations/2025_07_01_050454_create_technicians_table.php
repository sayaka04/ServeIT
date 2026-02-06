<?php

use App\Models\Technician;
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
        Schema::create('technicians', function (Blueprint $table) {
            $table->id(); // Primary Key
            // Foreign key to the users table (one-to-one relationship, or a user *can be* a technician)

            $table->foreignId('technician_user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('technician_code', 20)->unique();
            // Foreign key to the shops table
            $table->foreignId('shop_id')->nullable()->constrained('shops')->onDelete('set null'); // Technician can belong to a shop, or be independent
            $table->time('availability_start')->nullable();
            $table->time('availability_end')->nullable(); // Corrected from 'availability_and'
            $table->string('address')->nullable();
            $table->decimal('longitude', 10, 7)->nullable(); // Precision for geographical coordinates
            $table->decimal('latitude', 10, 7)->nullable();  // Precision for geographical coordinates
            $table->boolean('tesda_verified')->default(false);
            $table->boolean('home_service')->default(false);
            $table->integer('tesda_first_four');
            $table->integer('tesda_last_four');
            $table->decimal('weighted_score_rating', 5, 2);
            $table->decimal('success_rate', 5, 2);
            $table->integer('jobs_completed');
            $table->string('banner_picture')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};

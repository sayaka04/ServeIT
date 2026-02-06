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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  // User filing the report
            $table->unsignedBigInteger('reported_user_id');  // User being reported
            $table->unsignedBigInteger('category_id');  // Report category (FK to report_categories)
            $table->text('description');  // Report details
            $table->enum('status', ['pending', 'under_review', 'resolved', 'closed'])->default('pending');  // Report status
            $table->text('admin_notes')->nullable();
            $table->boolean('is_admin_report')->default(false);
            $table->text('file_path')->nullable(); // Path to the uploaded file [Allows images, documents, etc.] many files at once, converted to one file afterwards as pdf.
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reported_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('report_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

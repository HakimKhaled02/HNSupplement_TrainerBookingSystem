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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->json('specialties')->nullable(); // e.g., ["strength", "cardio", "yoga"]
            $table->json('certifications')->nullable(); // e.g., ["CPT", "Yoga Instructor"]
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('rating', 3, 2)->default(0.00)->nullable(); // Average rating
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->json('availability')->nullable(); // Store availability schedule
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};

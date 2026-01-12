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
        Schema::table('trainers', function (Blueprint $table) {
            $table->dropColumn(['bio', 'specialties', 'certifications', 'hourly_rate']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->text('bio')->nullable();
            $table->json('specialties')->nullable();
            $table->json('certifications')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
        });
    }
};

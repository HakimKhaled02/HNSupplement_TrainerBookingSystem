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
            $table->string('state')->nullable()->after('phone');
            $table->string('area')->nullable()->after('state');
            $table->string('category')->nullable()->after('area');
            $table->string('profile_picture')->nullable()->after('category');
            $table->string('qualification_file')->nullable()->after('profile_picture');
            $table->decimal('latitude', 10, 8)->nullable()->after('qualification_file');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->dropColumn(['state', 'area', 'category', 'profile_picture', 'qualification_file', 'latitude', 'longitude']);
        });
    }
};

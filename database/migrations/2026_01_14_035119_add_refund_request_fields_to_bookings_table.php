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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'refund_request_status')) {
                $table->enum('refund_request_status', ['pending', 'approved', 'rejected'])->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('bookings', 'refund_reason')) {
                $table->string('refund_reason')->nullable()->after('refund_request_status');
            }
            if (!Schema::hasColumn('bookings', 'refund_details')) {
                $table->text('refund_details')->nullable()->after('refund_reason');
            }
            if (!Schema::hasColumn('bookings', 'refund_requested_at')) {
                $table->timestamp('refund_requested_at')->nullable()->after('refund_details');
            }
            if (!Schema::hasColumn('bookings', 'refund_processed_at')) {
                $table->timestamp('refund_processed_at')->nullable()->after('refund_requested_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'refund_request_status',
                'refund_reason',
                'refund_details',
                'refund_requested_at',
                'refund_processed_at'
            ]);
        });
    }
};

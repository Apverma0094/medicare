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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('cancellation_reason')->nullable()->after('notes');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_reason');
            $table->enum('refund_status', ['none', 'requested', 'approved', 'processing', 'completed', 'rejected'])
                  ->default('none')->after('cancelled_at');
            $table->decimal('refund_amount', 10, 2)->default(0)->after('refund_status');
            $table->timestamp('refund_requested_at')->nullable()->after('refund_amount');
            $table->timestamp('refund_processed_at')->nullable()->after('refund_requested_at');
            $table->text('refund_notes')->nullable()->after('refund_processed_at');
            $table->string('refund_reference')->nullable()->after('refund_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_reason',
                'cancelled_at',
                'refund_status',
                'refund_amount',
                'refund_requested_at',
                'refund_processed_at',
                'refund_notes',
                'refund_reference'
            ]);
        });
    }
};

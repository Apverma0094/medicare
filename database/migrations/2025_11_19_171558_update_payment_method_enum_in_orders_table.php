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
        Schema::table('orders', function (Blueprint $table) {
            // First, change the column to a varchar to avoid enum constraints
            $table->string('payment_method', 20)->change();
        });
        
        // Then update it back to enum with the new values
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('cod', 'online', 'stripe') DEFAULT 'cod'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert to original enum values
            $table->enum('payment_method', ['cod', 'online'])->default('cod')->change();
        });
    }
};

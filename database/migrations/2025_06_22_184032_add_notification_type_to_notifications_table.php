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
        Schema::table('notifications', function (Blueprint $table) {
            // Add notification_type column to categorize notifications
            $table->string('notification_type')->default('general')->after('message');

            // Add index for better performance when querying by type
            $table->index(['stock_id', 'notification_type', 'read']);

            // Add index for cleanup operations
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['stock_id', 'notification_type', 'read']);
            $table->dropIndex(['created_at']);
            $table->dropColumn('notification_type');
        });
    }
};

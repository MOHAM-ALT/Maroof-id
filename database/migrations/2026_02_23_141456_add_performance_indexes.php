<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cards: composite indexes for common queries
        Schema::table('cards', function (Blueprint $table) {
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'is_public']);
            $table->index(['is_active', 'is_public']);
            $table->index('created_at');
        });

        // Card views: analytics query indexes
        Schema::table('card_views', function (Blueprint $table) {
            $table->index('country');
            $table->index('device_type');
            $table->index(['card_id', 'viewed_at']);
            $table->index('ip_address');
        });

        // Orders: date-based queries
        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_active']);
            $table->dropIndex(['user_id', 'is_public']);
            $table->dropIndex(['is_active', 'is_public']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('card_views', function (Blueprint $table) {
            $table->dropIndex(['country']);
            $table->dropIndex(['device_type']);
            $table->dropIndex(['card_id', 'viewed_at']);
            $table->dropIndex(['ip_address']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['created_at']);
        });
    }
};

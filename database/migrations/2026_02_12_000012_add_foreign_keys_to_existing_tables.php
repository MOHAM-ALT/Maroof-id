<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add foreign keys to existing tables
        Schema::table('templates', function (Blueprint $table) {
            if (!Schema::hasColumn('templates', 'designer_id')) {
                $table->foreignId('designer_id')->nullable()->constrained('designers')->onDelete('set null');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'coupon_id')) {
                $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
            }
            if (!Schema::hasColumn('orders', 'partner_id')) {
                $table->foreignId('partner_id')->nullable()->constrained('partners')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['designer_id']);
            $table->dropColumnIfExists('designer_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['coupon_id']);
            $table->dropColumnIfExists('coupon_id');
            $table->dropForeignKeyIfExists(['partner_id']);
            $table->dropColumnIfExists('partner_id');
        });
    }
};

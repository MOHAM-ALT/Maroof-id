<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->integer('employee_count')->default(1);
            $table->enum('plan', ['starter', 'professional', 'enterprise'])->default('starter');
            $table->decimal('annual_revenue', 12, 2)->nullable();
            $table->string('website')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamp('subscription_end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('business_account_id')->nullable()->constrained('business_accounts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['business_account_id']);
            $table->dropColumn('business_account_id');
        });
        Schema::dropIfExists('business_accounts');
    }
};

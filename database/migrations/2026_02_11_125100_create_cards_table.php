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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->nullable()->constrained()->onDelete('set null');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('bio')->nullable();
            
            // Personal Info
            $table->string('full_name');
            $table->string('job_title')->nullable();
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            
            // Media
            $table->string('profile_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('logo')->nullable();
            
            // NFC & QR
            $table->string('nfc_id')->unique()->nullable();
            $table->string('qr_code')->nullable();
            
            // Customization
            $table->json('design_settings')->nullable(); // Colors, fonts, layout
            $table->json('custom_fields')->nullable(); // Additional dynamic fields
            
            // Status & Analytics
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(true);
            $table->integer('views_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('user_id');
            $table->index('slug');
            $table->index('nfc_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_kits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('primary_color', 7)->default('#1d4ed8');
            $table->string('secondary_color', 7)->default('#3b82f6');
            $table->string('accent_color', 7)->default('#f59e0b');
            $table->string('text_color', 7)->default('#1f2937');
            $table->string('background_color', 7)->default('#ffffff');
            $table->string('font_family')->default('Cairo');
            $table->string('logo_path')->nullable();
            $table->string('icon_path')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->json('social_defaults')->nullable();
            $table->text('default_bio')->nullable();
            $table->string('default_company')->nullable();
            $table->string('default_website')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_kits');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_settings')) {
            return;
        }

        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('anthropic');
            // api_key رمزنگاری‌شده ذخیره می‌شود (cast: encrypted در مدل)، پس text.
            $table->text('api_key')->nullable();
            $table->string('model')->default('claude-opus-4-8');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};

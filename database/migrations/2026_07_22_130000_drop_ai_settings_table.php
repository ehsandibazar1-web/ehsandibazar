<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * حذف جدول ai_settings — این جدول برای «دستیار مقاله»ی ساده‌ی موقتی بود که
 * حذف شد. سیستم اصلی هوش مصنوعی از جدول‌های ai_provider_configs/settings
 * استفاده می‌کند، نه این. dropIfExists است، پس اگر جدول وجود نداشته باشد بی‌خطر است.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('ai_settings');
    }

    public function down(): void
    {
        // بازگردانی لازم نیست — این جدول دیگر استفاده نمی‌شود.
    }
};

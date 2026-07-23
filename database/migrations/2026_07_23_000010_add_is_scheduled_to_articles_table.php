<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * نشانگرِ صریحِ «زمان‌بندی‌شده» برای انتشارِ خودکار. چون statusِ فارسی بولین است (۰ پیش‌نویس/۱ منتشر)
 * و مرحله‌ی «scheduled» جدا ندارد، این ستون تضمین می‌کند فقط مقاله‌هایی که ادمین «عمداً» زمان‌بندی
 * کرده (نه هر پیش‌نویسی که تصادفاً published_atِ گذشته دارد) خودکار منتشر شوند — «بدونِ دردسر».
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (! Schema::hasColumn('articles', 'is_scheduled')) {
                $table->boolean('is_scheduled')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'is_scheduled')) {
                $table->dropColumn('is_scheduled');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // توکن‌های API ایمپورت — فقط هشِ توکن ذخیره می‌شود؛ متن کامل فقط یک‌بار هنگام ساخت نمایش داده می‌شود
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token_hash', 64)->unique();
            $table->string('prefix', 12); // برای شناسایی توکن در پنل بدون لو رفتن متن کامل
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        // نکته‌ی انتقال: بلوک افزودن api_token_id به جدول import_logs حذف شد،
        // چون جدول import_logs در گروه «ایمپورت» است و هنوز به سایت فارسی منتقل نشده.
        // وقتی آن گروه منتقل شود، آن FK در همان‌جا اضافه می‌شود.
    }

    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
    }
};

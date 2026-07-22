<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * تنظیمات هوش مصنوعی (کلید API و مدل) — تک‌ردیفی.
 * کلید API با cast «encrypted» رمزنگاری‌شده در دیتابیس ذخیره می‌شود، پس
 * حتی اگر کسی به دیتابیس دسترسی پیدا کند، کلید به‌صورت متن خام دیده نمی‌شود.
 */
class AiSetting extends Model
{
    protected $fillable = [
        'provider',
        'api_key',
        'model',
    ];

    protected $casts = [
        'api_key' => 'encrypted',
    ];

    /**
     * ردیف تنظیمات فعلی را برمی‌گرداند (اگر نبود، می‌سازد).
     */
    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['provider' => 'anthropic', 'model' => 'claude-opus-4-8']
        );
    }
}

<?php

namespace App\Cms\Enums;

/**
 * زبان‌های مجازِ محتوا (docs/CMS-CORE-CONTRACT.md، لایه ۴). سایت فارسی عملاً fa را استفاده می‌کند؛
 * en/tr برای تقارن با سایت انگلیسی و محتوای چندزبانه‌ی آینده تعریف شده‌اند.
 */
enum Locale: string
{
    case En = 'en';
    case Tr = 'tr';
    case Fa = 'fa';

    public function label(): string
    {
        return match ($this) {
            self::En => 'English',
            self::Tr => 'Turkish',
            self::Fa => 'Persian',
        };
    }

    public static function options(): array
    {
        return array_reduce(
            self::cases(),
            fn (array $carry, self $case): array => $carry + [$case->value => $case->label()],
            [],
        );
    }
}

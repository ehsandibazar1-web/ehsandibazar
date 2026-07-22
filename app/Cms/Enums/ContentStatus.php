<?php

namespace App\Cms\Enums;

/**
 * وضعیتِ استانداردِ انتشارِ محتوا در هر دو پروژه (docs/CMS-CORE-CONTRACT.md، لایه ۴).
 * مقادیرِ رشته‌ای ثابت‌اند — مبنای scopePublished و منطقِ زمان‌بندی.
 */
enum ContentStatus: string
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Scheduled => 'Scheduled',
            self::Published => 'Published',
            self::Archived => 'Archived',
        };
    }

    /** رنگِ Badge در Filament. */
    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Scheduled => 'warning',
            self::Published => 'success',
            self::Archived => 'danger',
        };
    }

    /** برای Select در فرم‌های Filament: [value => label]. */
    public static function options(): array
    {
        return array_reduce(
            self::cases(),
            fn (array $carry, self $case): array => $carry + [$case->value => $case->label()],
            [],
        );
    }
}

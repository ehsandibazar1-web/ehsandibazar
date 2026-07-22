<?php

namespace Tests\Unit;

use App\Cms\Concerns;
use App\Cms\Contracts;
use App\Cms\Enums\ContentStatus;
use App\Cms\Enums\Locale;
use App\Cms\MorphMap;
use Tests\TestCase;

/**
 * تستِ قراردادِ CMS Core (docs/CMS-CORE-CONTRACT.md، لایه ۶) — مطمئن می‌شود ساختارِ پایه
 * دست‌نخورده می‌ماند تا drift زودتر از production گیر بیفتد.
 */
class CmsCoreContractTest extends TestCase
{
    public function test_content_status_enum_has_the_four_canonical_states(): void
    {
        $this->assertSame(
            ['draft', 'scheduled', 'published', 'archived'],
            array_map(fn (ContentStatus $c) => $c->value, ContentStatus::cases()),
        );
        $this->assertSame('success', ContentStatus::Published->color());
        $this->assertArrayHasKey('draft', ContentStatus::options());
    }

    public function test_locale_enum_has_en_tr_fa(): void
    {
        $this->assertSame(['en', 'tr', 'fa'], array_map(fn (Locale $l) => $l->value, Locale::cases()));
    }

    public function test_capability_interfaces_exist(): void
    {
        foreach ([
            Contracts\Sluggable::class,
            Contracts\Localizable::class,
            Contracts\Publishable::class,
            Contracts\Taggable::class,
            Contracts\SeoOptimizable::class,
            Contracts\HasFeaturedMedia::class,
            Contracts\CmsContent::class,
        ] as $interface) {
            $this->assertTrue(interface_exists($interface), "{$interface} باید interface باشد");
        }
    }

    public function test_cms_content_composes_all_six_capabilities(): void
    {
        $implemented = class_implements(new class implements Contracts\CmsContent
        {
            // پیاده‌سازیِ حداقلی فقط برای بررسیِ ترکیبِ interface — بدنه مهم نیست
            public function getSlug(): string { return ''; }
            public static function makeSlug(string $title, ?string $locale = null): string { return ''; }
            public function getLocale(): string { return 'fa'; }
            public function translation(): \Illuminate\Database\Eloquent\Relations\BelongsTo { throw new \LogicException; }
            public function translations(): \Illuminate\Database\Eloquent\Relations\HasMany { throw new \LogicException; }
            public function isPublished(): bool { return false; }
            public function getPublishedAt(): ?\DateTimeInterface { return null; }
            public function scopePublished(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder { return $query; }
            public function tags(): \Illuminate\Database\Eloquent\Relations\MorphToMany { throw new \LogicException; }
            public function keywords(): \Illuminate\Database\Eloquent\Relations\MorphMany { throw new \LogicException; }
            public function getSeoTitle(): ?string { return null; }
            public function getMetaDescription(): ?string { return null; }
            public function getCanonicalUrl(): ?string { return null; }
            public function getRobots(): ?string { return null; }
            public function getImagePath(): ?string { return null; }
            public function getImageAlt(): ?string { return null; }
            public function getOptimizedImageUrlAttribute(): ?string { return null; }
        });

        foreach ([
            Contracts\Sluggable::class, Contracts\Localizable::class, Contracts\Publishable::class,
            Contracts\Taggable::class, Contracts\SeoOptimizable::class, Contracts\HasFeaturedMedia::class,
        ] as $capability) {
            $this->assertContains($capability, $implemented);
        }
    }

    public function test_service_contracts_exist(): void
    {
        foreach ([
            Contracts\MediaLibrary::class,
            Contracts\SeoService::class,
            Contracts\ContentAssistant::class,
        ] as $interface) {
            $this->assertTrue(interface_exists($interface), "{$interface} باید interface باشد");
        }
    }

    public function test_concern_traits_exist(): void
    {
        foreach ([
            Concerns\HasSlug::class,
            Concerns\HasLocale::class,
            Concerns\HasPublishing::class,
            Concerns\HasContentTags::class,
            Concerns\HasSeoMeta::class,
            Concerns\ProvidesFeaturedMedia::class,
            Concerns\LogsContentActivity::class,
        ] as $trait) {
            $this->assertTrue(trait_exists($trait), "{$trait} باید trait باشد");
        }
    }

    public function test_morph_map_has_stable_aliases_and_is_not_yet_enforced(): void
    {
        $map = MorphMap::map();

        // aliasهای رشته‌ای ثابت — کلیدها هرگز نباید به namespace وابسته شوند
        $this->assertSame(\App\Models\Media::class, $map['media']);
        $this->assertArrayHasKey('article', $map);
        $this->assertArrayHasKey('brand_memory_value', $map);

        // هنوز فعال نشده (موج ۴ با backfill فعالش می‌کند)
        $this->assertFalse(
            \Illuminate\Database\Eloquent\Relations\Relation::requiresMorphMap(),
            'MorphMap نباید قبل از backfillِ موج ۴ enforce شده باشد',
        );
    }
}

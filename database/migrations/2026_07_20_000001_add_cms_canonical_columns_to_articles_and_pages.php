<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * موج ۴a — ستون‌های canonicalِ CMS Core Contract را به جدول‌های زنده‌ی articles/pages «اضافه»
 * می‌کند (فقط ADD COLUMN، همه nullable). هیچ ستونِ موجودی تغییر نمی‌کند و هیچ کدی هنوز این‌ها را
 * نمی‌خواند — پس storefront/sitemap/سئو بایت‌به‌بایت بی‌تغییر می‌ماند (docs/SEO-RED-LINE.md).
 *
 * ستون‌های legacy که همین کار را می‌کنند دست‌نخورده می‌مانند و «پل» می‌خورند (نه جایگزین):
 * lang↔locale، status(بولین)↔Publishable، seo(جدولِ جدا)↔ستون‌های سئو. نگاه کنید به docs/WAVE-4-PLAN.md.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $this->addIfMissing($table, 'articles', [
                'translation_of' => fn () => $table->unsignedBigInteger('translation_of')->nullable()->index(),
                'excerpt' => fn () => $table->text('excerpt')->nullable(),
                'faqs' => fn () => $table->json('faqs')->nullable(),
                'seo_title' => fn () => $table->string('seo_title')->nullable(),
                'meta_description' => fn () => $table->text('meta_description')->nullable(),
                'canonical_url' => fn () => $table->string('canonical_url')->nullable(),
                'robots' => fn () => $table->string('robots')->nullable(),
                'og_title' => fn () => $table->string('og_title')->nullable(),
                'og_description' => fn () => $table->text('og_description')->nullable(),
                'image_path' => fn () => $table->string('image_path')->nullable(),
                'image_alt' => fn () => $table->string('image_alt')->nullable(),
                'author_name' => fn () => $table->string('author_name')->nullable(),
                'reading_time' => fn () => $table->unsignedInteger('reading_time')->nullable(),
                'views' => fn () => $table->unsignedInteger('views')->default(0),
                'published_at' => fn () => $table->timestamp('published_at')->nullable(),
                'hero_image_prompt' => fn () => $table->text('hero_image_prompt')->nullable(),
                'thumbnail_image_prompt' => fn () => $table->text('thumbnail_image_prompt')->nullable(),
                'og_image_prompt' => fn () => $table->text('og_image_prompt')->nullable(),
                'social_image_prompt' => fn () => $table->text('social_image_prompt')->nullable(),
            ]);
        });

        Schema::table('pages', function (Blueprint $table) {
            $this->addIfMissing($table, 'pages', [
                'translation_of' => fn () => $table->unsignedBigInteger('translation_of')->nullable()->index(),
                'faqs' => fn () => $table->json('faqs')->nullable(),
                'seo_title' => fn () => $table->string('seo_title')->nullable(),
                'meta_description' => fn () => $table->text('meta_description')->nullable(),
                'meta_keywords' => fn () => $table->string('meta_keywords')->nullable(),
                'canonical_url' => fn () => $table->string('canonical_url')->nullable(),
                'robots' => fn () => $table->string('robots')->nullable(),
                'og_title' => fn () => $table->string('og_title')->nullable(),
                'og_description' => fn () => $table->text('og_description')->nullable(),
                'image_path' => fn () => $table->string('image_path')->nullable(),
                'image_alt' => fn () => $table->string('image_alt')->nullable(),
                'published_at' => fn () => $table->timestamp('published_at')->nullable(),
                'hero_image_prompt' => fn () => $table->text('hero_image_prompt')->nullable(),
                'thumbnail_image_prompt' => fn () => $table->text('thumbnail_image_prompt')->nullable(),
                'og_image_prompt' => fn () => $table->text('og_image_prompt')->nullable(),
                'social_image_prompt' => fn () => $table->text('social_image_prompt')->nullable(),
            ]);
        });
    }

    /** فقط ستون‌هایی را می‌سازد که هنوز نیستند — امن در برابر اجرای دوباره. */
    private function addIfMissing(Blueprint $table, string $tableName, array $columns): void
    {
        foreach ($columns as $name => $builder) {
            if (! Schema::hasColumn($tableName, $name)) {
                $builder();
            }
        }
    }

    public function down(): void
    {
        $articleCols = ['translation_of', 'excerpt', 'faqs', 'seo_title', 'meta_description', 'canonical_url',
            'robots', 'og_title', 'og_description', 'image_path', 'image_alt', 'author_name', 'reading_time',
            'views', 'published_at', 'hero_image_prompt', 'thumbnail_image_prompt', 'og_image_prompt', 'social_image_prompt'];
        $pageCols = ['translation_of', 'faqs', 'seo_title', 'meta_description', 'meta_keywords', 'canonical_url',
            'robots', 'og_title', 'og_description', 'image_path', 'image_alt', 'published_at',
            'hero_image_prompt', 'thumbnail_image_prompt', 'og_image_prompt', 'social_image_prompt'];

        Schema::table('articles', function (Blueprint $table) use ($articleCols) {
            foreach ($articleCols as $c) {
                if (Schema::hasColumn('articles', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
        Schema::table('pages', function (Blueprint $table) use ($pageCols) {
            foreach ($pageCols as $c) {
                if (Schema::hasColumn('pages', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};

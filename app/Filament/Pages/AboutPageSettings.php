<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * تنظیماتِ صفحه‌ی «درباره» — پورتِ صفحه‌ی About سایتِ انگلیسی روی یک انبارِ تنظیماتِ مستقلِ تازه
 * (جدولِ site_settings). فقط با SiteSetting::get/getJson/set کار می‌کند و هیچ ربطی به
 * تنظیماتِ storefrontِ فعلیِ فروشگاه ندارد؛ ذخیره‌ی این تنظیمات سایتِ زنده را تغییر نمی‌دهد.
 * زبان‌ها: فارسی (اصلی) و انگلیسی — کلیدها به‌صورت about.fa.* و about.en.* ذخیره می‌شوند.
 */
class AboutPageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'About Page Settings';

    protected static ?string $title = 'تنظیماتِ صفحه‌ی درباره';

    protected string $view = 'filament.pages.about-page-settings';

    public ?array $data = [];

    // کلیدهای متنی ساده — برای هر دو زبان یکسان تعریف می‌شوند
    private const TEXT_KEYS = [
        'hero_name', 'hero_title', 'hero_bio', 'hero_cta_text', 'hero_cta_url',
        'certs_heading', 'gallery_heading', 'timeline_heading',
        'cta_title', 'cta_description', 'cta_button_text', 'cta_button_url',
        'seo_title', 'seo_description',
        // باکسِ نویسنده‌ی پایانِ هر مقاله (blog-post) — عکسش همان hero_image بالاست
        'author_box_title', 'author_box_subtitle', 'author_box_text', 'author_box_button_text',
    ];

    // کلیدهای فایل (عکس) — مقدارشان مسیر فایل روی دیسک public است
    private const FILE_KEYS = [
        'hero_image', 'cta_bg_image', 'seo_og_image',
    ];

    // کلیدهای ریپیتر — به‌صورت JSON ذخیره می‌شوند
    private const REPEATER_KEYS = [
        'stats', 'certificates', 'gallery', 'timeline',
    ];

    public function mount(): void
    {
        $state = [];

        foreach (['fa', 'en'] as $locale) {
            foreach (self::TEXT_KEYS as $key) {
                $state[$locale][$key] = SiteSetting::get("about.$locale.$key");
            }
            foreach (self::FILE_KEYS as $key) {
                $state[$locale][$key] = SiteSetting::get("about.$locale.$key");
            }
            foreach (self::REPEATER_KEYS as $key) {
                $state[$locale][$key] = SiteSetting::getJson("about.$locale.$key");
            }
        }

        $this->form->fill($state);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('فارسی — قهرمان (Hero)')
                    ->schema(self::heroFields('fa')),
                Section::make('فارسی — آمار')
                    ->schema([self::statsRepeater('fa')])
                    ->collapsed(),
                Section::make('فارسی — گواهی‌نامه‌ها و افتخارات')
                    ->schema(self::certificatesFields('fa'))
                    ->collapsed(),
                Section::make('فارسی — گالریِ درباره')
                    ->schema(self::galleryFields('fa'))
                    ->collapsed(),
                Section::make('فارسی — خطِ زمانی')
                    ->schema(self::timelineFields('fa'))
                    ->collapsed(),
                Section::make('فارسی — بخشِ فراخوان (CTA)')
                    ->schema(self::ctaFields('fa'))
                    ->collapsed(),
                Section::make('فارسی — باکسِ نویسنده (پایانِ هر مقاله)')
                    ->schema(self::authorBoxFields('fa'))
                    ->collapsed(),
                Section::make('فارسی — سئو')
                    ->schema(self::seoFields('fa'))
                    ->collapsed(),

                Section::make('انگلیسی — قهرمان (Hero)')
                    ->schema(self::heroFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — آمار')
                    ->schema([self::statsRepeater('en')])
                    ->collapsed(),
                Section::make('انگلیسی — گواهی‌نامه‌ها و افتخارات')
                    ->schema(self::certificatesFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — گالریِ درباره')
                    ->schema(self::galleryFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — خطِ زمانی')
                    ->schema(self::timelineFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — بخشِ فراخوان (CTA)')
                    ->schema(self::ctaFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — باکسِ نویسنده (پایانِ هر مقاله)')
                    ->schema(self::authorBoxFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — سئو')
                    ->schema(self::seoFields('en'))
                    ->collapsed(),
            ])
            ->statePath('data');
    }

    private static function imageUpload(string $key): FileUpload
    {
        return FileUpload::make($key)
            ->image()
            ->disk('public')
            ->directory('site-settings')
            ->nullable();
    }

    private static function heroFields(string $l): array
    {
        return [
            self::imageUpload("$l.hero_image")
                ->label('عکسِ قهرمان'),
            TextInput::make("$l.hero_name")
                ->label('نام'),
            TextInput::make("$l.hero_title")
                ->label('عنوانِ حرفه‌ای')
                ->helperText('زیرِ نام نمایش داده می‌شود، مثلاً «مربیِ هنرهای رزمی و دفاعِ شخصی».'),
            Textarea::make("$l.hero_bio")
                ->label('بیوگرافی')
                ->rows(5),
            TextInput::make("$l.hero_cta_text")
                ->label('متنِ دکمه‌ی فراخوان (اختیاری)')
                ->helperText('برای پنهان‌کردنِ دکمه هر دو فیلدِ فراخوان را خالی بگذارید — اختیاری است و به‌طورِ پیش‌فرض خاموش.'),
            TextInput::make("$l.hero_cta_url")
                ->label('آدرسِ دکمه‌ی فراخوان (اختیاری)'),
        ];
    }

    private static function statsRepeater(string $l): Repeater
    {
        return Repeater::make("$l.stats")
            ->label('آمار')
            ->schema([
                TextInput::make('value')->label('مقدار')->required()->helperText('مثلاً «۱۲+» یا «هزاران»'),
                TextInput::make('label')->label('برچسب')->required()->helperText('مثلاً «سالِ تجربه‌ی تدریس»'),
            ])
            ->defaultItems(0)
            ->reorderable()
            ->addActionLabel('افزودنِ آمار');
    }

    private static function certificatesFields(string $l): array
    {
        return [
            TextInput::make("$l.certs_heading")
                ->label('عنوانِ بخش'),
            Repeater::make("$l.certificates")
                ->label('گواهی‌نامه‌ها')
                ->schema([
                    self::imageUpload('image')
                        ->label('تصویرِ گواهی‌نامه')
                        ->helperText('اختیاری — اگر خالی بماند یک تصویرِ پیش‌فرض نمایش داده می‌شود.'),
                    TextInput::make('title')
                        ->label('عنوان')
                        ->required(),
                    TextInput::make('subtitle')
                        ->label('زیرعنوان')
                        ->nullable(),
                    Textarea::make('description')
                        ->label('توضیحات')
                        ->rows(3)
                        ->nullable(),
                    TextInput::make('sort_order')
                        ->label('ترتیبِ نمایش')
                        ->numeric()
                        ->nullable()
                        ->helperText('عددِ کوچک‌تر زودتر نمایش داده می‌شود. برای حفظِ ترتیبِ افزودن خالی بگذارید.'),
                ])
                ->defaultItems(0)
                ->reorderable()
                ->addActionLabel('افزودنِ گواهی‌نامه'),
        ];
    }

    private static function galleryFields(string $l): array
    {
        return [
            TextInput::make("$l.gallery_heading")
                ->label('عنوانِ بخش')
                ->helperText('فقط زمانی نمایش داده می‌شود که دستِ‌کم یک تصویر اضافه شده باشد.'),
            Repeater::make("$l.gallery")
                ->label('تصاویرِ گالری')
                ->schema([
                    self::imageUpload('image')
                        ->label('تصویر')
                        ->required(),
                    TextInput::make('alt')
                        ->label('متنِ جایگزین (ALT)')
                        ->required()
                        ->helperText('تصویر را برای دسترس‌پذیری و سئو توصیف می‌کند.'),
                    TextInput::make('sort_order')
                        ->label('ترتیبِ نمایش')
                        ->numeric()
                        ->nullable()
                        ->helperText('عددِ کوچک‌تر زودتر نمایش داده می‌شود. برای حفظِ ترتیبِ افزودن خالی بگذارید.'),
                ])
                ->defaultItems(0)
                ->reorderable()
                ->addActionLabel('افزودنِ تصویر'),
        ];
    }

    private static function timelineFields(string $l): array
    {
        return [
            TextInput::make("$l.timeline_heading")
                ->label('عنوانِ بخش'),
            Repeater::make("$l.timeline")
                ->label('آیتم‌های خطِ زمانی')
                ->schema([
                    TextInput::make('year')
                        ->label('سال')
                        ->required(),
                    TextInput::make('title')
                        ->label('عنوان')
                        ->required(),
                    Textarea::make('description')
                        ->label('توضیحات')
                        ->rows(3)
                        ->nullable(),
                    TextInput::make('sort_order')
                        ->label('ترتیبِ نمایش')
                        ->numeric()
                        ->nullable()
                        ->helperText('عددِ کوچک‌تر زودتر نمایش داده می‌شود. برای حفظِ ترتیبِ افزودن خالی بگذارید.'),
                ])
                ->defaultItems(0)
                ->reorderable()
                ->addActionLabel('افزودنِ آیتمِ خطِ زمانی'),
        ];
    }

    private static function ctaFields(string $l): array
    {
        return [
            TextInput::make("$l.cta_title")
                ->label('عنوان (اختیاری)'),
            Textarea::make("$l.cta_description")
                ->label('توضیحات (اختیاری)')
                ->rows(2),
            TextInput::make("$l.cta_button_text")
                ->label('متنِ دکمه'),
            TextInput::make("$l.cta_button_url")
                ->label('آدرسِ دکمه'),
            self::imageUpload("$l.cta_bg_image")
                ->label('تصویرِ پس‌زمینه (اختیاری)')
                ->helperText('برای حفظِ پس‌زمینه‌ی گرادیانِ تیره‌ی پیش‌فرض خالی بگذارید.'),
        ];
    }

    private static function authorBoxFields(string $l): array
    {
        return [
            TextInput::make("$l.author_box_title")
                ->label('عنوان')
                ->helperText('در پایانِ هر مقاله‌ی وبلاگ نمایش داده می‌شود. برای حفظِ متنِ پیش‌فرضِ فعلی خالی بگذارید.'),
            TextInput::make("$l.author_box_subtitle")
                ->label('زیرعنوان')
                ->helperText('خطِ طلاییِ کوچکِ زیرِ عنوان، مثلاً حرفه و مدارکِ شما.'),
            Textarea::make("$l.author_box_text")
                ->label('متنِ معرفی')
                ->rows(6)
                ->helperText('برای جداکردنِ پاراگراف‌ها بینِ آن‌ها یک خطِ خالی بگذارید. برای حفظِ متنِ پیش‌فرضِ فعلی فیلد را خالی بگذارید.'),
            TextInput::make("$l.author_box_button_text")
                ->label('متنِ دکمه')
                ->helperText('این دکمه همیشه به صفحه‌ی درباره لینک می‌شود.'),
        ];
    }

    private static function seoFields(string $l): array
    {
        return [
            TextInput::make("$l.seo_title")
                ->label('عنوانِ متا'),
            Textarea::make("$l.seo_description")
                ->label('توضیحاتِ متا')
                ->rows(3),
            self::imageUpload("$l.seo_og_image")
                ->label('تصویرِ Open Graph')
                ->helperText('هنگامِ اشتراک‌گذاریِ این صفحه در شبکه‌های اجتماعی به‌عنوانِ تصویرِ پیش‌نمایش نمایش داده می‌شود.'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (['fa', 'en'] as $locale) {
            foreach (array_merge(self::TEXT_KEYS, self::FILE_KEYS) as $key) {
                SiteSetting::set("about.$locale.$key", self::normalizeUpload($state[$locale][$key] ?? null), 'about');
            }

            foreach (['certificates', 'gallery'] as $key) {
                $items = array_map(
                    fn ($item) => [...$item, 'image' => self::normalizeUpload($item['image'] ?? null)],
                    array_values($state[$locale][$key] ?? [])
                );
                SiteSetting::set("about.$locale.$key", json_encode($items, JSON_UNESCAPED_UNICODE), 'about');
            }

            foreach (['stats', 'timeline'] as $key) {
                SiteSetting::set(
                    "about.$locale.$key",
                    json_encode(array_values($state[$locale][$key] ?? []), JSON_UNESCAPED_UNICODE),
                    'about'
                );
            }
        }

        Notification::make()
            ->success()
            ->title('تنظیماتِ صفحه‌ی درباره ذخیره شد')
            ->send();
    }

    // FileUpload گاهی مقدار را به‌صورت آرایه برمی‌گرداند —
    // اولین مسیر را برمی‌داریم تا در ستون متنی ذخیره‌شدنی باشد
    private static function normalizeUpload($value)
    {
        if (is_array($value)) {
            return array_values(array_filter($value))[0] ?? null;
        }

        return $value;
    }
}

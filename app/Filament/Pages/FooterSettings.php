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
 * تنظیماتِ فوتر — پورتِ صفحه‌ی Footer سایتِ انگلیسی روی انبارِ تنظیماتِ مستقلِ تازه (site_settings).
 * فقط با SiteSetting::get/getJson/set کار می‌کند و به تنظیماتِ storefrontِ فعلیِ فروشگاه
 * دست نمی‌زند؛ ذخیره‌ی این تنظیمات سایتِ زنده را تغییر نمی‌دهد. زبان‌ها: فارسی (اصلی) و انگلیسی.
 */
class FooterSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewColumns;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Footer Settings';

    protected static ?string $title = 'تنظیماتِ فوتر';

    protected string $view = 'filament.pages.footer-settings';

    public ?array $data = [];

    // کلیدهای متنی ساده — برای هر دو زبان یکسان تعریف می‌شوند
    private const TEXT_KEYS = [
        'newsletter_title', 'newsletter_description', 'newsletter_placeholder', 'newsletter_button',
        'description', 'copyright',
        'contact_email', 'contact_phone', 'contact_address',
    ];

    // کلیدهای فایل (عکس) — مقدارشان مسیر فایل روی دیسک public است
    private const FILE_KEYS = [
        'bg_image', 'logo',
    ];

    // کلیدهای ریپیتر — به‌صورت JSON ذخیره می‌شوند
    private const REPEATER_KEYS = [
        'columns', 'socials',
    ];

    public function mount(): void
    {
        $state = [];

        foreach (['fa', 'en'] as $locale) {
            foreach (self::TEXT_KEYS as $key) {
                $state[$locale][$key] = SiteSetting::get("footer.$locale.$key");
            }
            foreach (self::FILE_KEYS as $key) {
                $state[$locale][$key] = SiteSetting::get("footer.$locale.$key");
            }
            foreach (self::REPEATER_KEYS as $key) {
                $state[$locale][$key] = SiteSetting::getJson("footer.$locale.$key");
            }
        }

        $this->form->fill($state);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('فارسی — نوارِ خبرنامه')
                    ->description('نوارِ طلاییِ بالای فوتر. برای حفظِ متنِ پیش‌فرضِ فعلی هر فیلد را خالی بگذارید.')
                    ->schema(self::newsletterFields('fa')),
                Section::make('فارسی — ظاهرِ فوتر')
                    ->schema(self::lookFields('fa'))
                    ->collapsed(),
                Section::make('فارسی — ستون‌های لینک')
                    ->description('ستون‌های لینکِ نمایش‌داده‌شده در فوتر. برای حفظِ ستون‌های پیش‌فرضِ فعلی خالی بگذارید.')
                    ->schema([self::columnsRepeater('fa')])
                    ->collapsed(),
                Section::make('فارسی — شبکه‌های اجتماعی و تماس')
                    ->description('اختیاری — فقط پس از پرکردن در فوتر نمایش داده می‌شوند.')
                    ->schema(self::socialContactFields('fa'))
                    ->collapsed(),

                Section::make('انگلیسی — نوارِ خبرنامه')
                    ->schema(self::newsletterFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — ظاهرِ فوتر')
                    ->schema(self::lookFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — ستون‌های لینک')
                    ->schema([self::columnsRepeater('en')])
                    ->collapsed(),
                Section::make('انگلیسی — شبکه‌های اجتماعی و تماس')
                    ->schema(self::socialContactFields('en'))
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

    private static function newsletterFields(string $l): array
    {
        return [
            TextInput::make("$l.newsletter_title")->label('عنوان'),
            TextInput::make("$l.newsletter_description")->label('توضیحات'),
            TextInput::make("$l.newsletter_placeholder")->label('متنِ راهنمای کادرِ ایمیل'),
            TextInput::make("$l.newsletter_button")->label('متنِ دکمه'),
        ];
    }

    private static function lookFields(string $l): array
    {
        return [
            self::imageUpload("$l.bg_image")
                ->label('تصویرِ پس‌زمینه‌ی فوتر')
                ->helperText('برای حفظِ پس‌زمینه‌ی فعلی خالی بگذارید.'),
            self::imageUpload("$l.logo")
                ->label('لوگوی فوتر')
                ->helperText('برای حفظِ لوگوی فعلی خالی بگذارید.'),
            Textarea::make("$l.description")
                ->label('توضیحاتِ فوتر (اختیاری)')
                ->rows(2)
                ->helperText('متنِ کوتاهی که زیرِ لوگوی فوتر نمایش داده می‌شود. تا وقتی خالی باشد پنهان است.'),
            TextInput::make("$l.copyright")
                ->label('متنِ کپی‌رایت')
                ->helperText('پس از © و سالِ جاری نمایش داده می‌شود — مثلاً «احسان دیبازر. تمامِ حقوق محفوظ است». برای حفظِ پیش‌فرض خالی بگذارید.'),
        ];
    }

    private static function columnsRepeater(string $l): Repeater
    {
        return Repeater::make("$l.columns")
            ->label('ستون‌ها')
            ->schema([
                TextInput::make('title')
                    ->label('عنوانِ ستون')
                    ->required(),
                Repeater::make('links')
                    ->label('لینک‌ها')
                    ->schema([
                        TextInput::make('label')->label('برچسب')->required(),
                        TextInput::make('url')->label('آدرس')->required()
                            ->helperText('نسبی مثل /about یا /en/blog — یا یک لینکِ کاملِ https://.'),
                    ])
                    ->defaultItems(0)
                    ->reorderable()
                    ->addActionLabel('افزودنِ لینک'),
            ])
            ->defaultItems(0)
            ->reorderable()
            ->addActionLabel('افزودنِ ستون');
    }

    private static function socialContactFields(string $l): array
    {
        return [
            Repeater::make("$l.socials")
                ->label('لینک‌های شبکه‌های اجتماعی')
                ->schema([
                    TextInput::make('label')->label('نام')->required()->helperText('مثلاً اینستاگرام، یوتیوب، تلگرام'),
                    TextInput::make('url')->label('آدرس')->required(),
                ])
                ->defaultItems(0)
                ->reorderable()
                ->addActionLabel('افزودنِ لینکِ اجتماعی'),
            TextInput::make("$l.contact_email")->label('ایمیلِ تماس (اختیاری)'),
            TextInput::make("$l.contact_phone")->label('تلفنِ تماس (اختیاری)'),
            TextInput::make("$l.contact_address")->label('نشانی (اختیاری)'),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (['fa', 'en'] as $locale) {
            foreach (array_merge(self::TEXT_KEYS, self::FILE_KEYS) as $key) {
                SiteSetting::set("footer.$locale.$key", self::normalizeUpload($state[$locale][$key] ?? null), 'footer');
            }

            $columns = array_map(
                fn ($col) => [...$col, 'links' => array_values($col['links'] ?? [])],
                array_values($state[$locale]['columns'] ?? [])
            );
            SiteSetting::set("footer.$locale.columns", json_encode($columns, JSON_UNESCAPED_UNICODE), 'footer');

            SiteSetting::set(
                "footer.$locale.socials",
                json_encode(array_values($state[$locale]['socials'] ?? []), JSON_UNESCAPED_UNICODE),
                'footer'
            );
        }

        Notification::make()
            ->success()
            ->title('تنظیماتِ فوتر ذخیره شد')
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

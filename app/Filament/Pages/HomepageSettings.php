<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * تنظیماتِ صفحه‌ی خانه — پورتِ صفحه‌ی Homepage سایتِ انگلیسی روی انبارِ تنظیماتِ مستقلِ تازه
 * (site_settings). فقط با SiteSetting::get/getJson/set کار می‌کند و به تنظیماتِ storefrontِ
 * فعلیِ فروشگاه دست نمی‌زند؛ ذخیره‌ی این تنظیمات سایتِ زنده را تغییر نمی‌دهد. زبان‌ها: فارسی (اصلی) و انگلیسی.
 */
class HomepageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Homepage Settings';

    protected static ?string $title = 'تنظیماتِ صفحه‌ی خانه';

    protected string $view = 'filament.pages.homepage-settings';

    public ?array $data = [];

    // کلیدهای متنی ساده — برای هر دو زبان یکسان تعریف می‌شوند
    private const TEXT_KEYS = [
        'hero1_title', 'hero1_sub',
        'hero2_title', 'hero2_sub',
        'hero3_title', 'hero3_sub',
        'video1_caption', 'video1_embed',
        'video2_caption', 'video2_embed',
        'video3_caption', 'video3_embed',
        'app_title', 'app_subtitle', 'app_text', 'app_button_label',
        'courses_title', 'courses_subtitle',
        'course1_label', 'course2_label', 'course3_label',
        'course1_link', 'course2_link', 'course3_link',
        'members_title', 'members_subtitle', 'members_button_label',
        // insta_url = لینک پروفایل اینستاگرام؛ به‌عنوان fallback دکمهٔ Follow هر دو ردیف استفاده می‌شود
        'insta_url',
        // ویترین اینستاگرام (Instagram Showcase) — ردیف اول. کلید آدرس embed به‌صورت تاریخی
        // insta_embed_url است (بدون بخش showcase_) و باید در فرم دقیقاً همین کلید پاس شود
        'insta_showcase_enabled', 'insta_embed_url',
        'insta_showcase_title', 'insta_showcase_subtitle',
        'insta_showcase_button_text', 'insta_showcase_button_url',
        // ردیف دوم ویترین اینستاگرام — کاملاً اختیاری و پیش‌فرض غیرفعال
        'insta_showcase2_enabled', 'insta_showcase2_embed_url',
        'insta_showcase2_title', 'insta_showcase2_subtitle',
        'insta_showcase2_button_text', 'insta_showcase2_button_url',
    ];

    // کلیدهای فایل (عکس/ویدیو) — مقدارشان مسیر فایل روی دیسک public است
    private const FILE_KEYS = [
        'hero1_image', 'hero2_image', 'hero3_image',
        'video1_file', 'video2_file', 'video3_file',
        'video1_thumb', 'video2_thumb', 'video3_thumb',
        'app_image',
        'course1_image', 'course2_image', 'course3_image',
        'insta_showcase_fallback_image',
        'insta_showcase2_fallback_image',
    ];

    public function mount(): void
    {
        $state = [];

        foreach (['fa', 'en'] as $locale) {
            foreach (self::TEXT_KEYS as $key) {
                $state[$locale][$key] = SiteSetting::get("home.$locale.$key");
            }
            foreach (self::FILE_KEYS as $key) {
                $state[$locale][$key] = SiteSetting::get("home.$locale.$key");
            }
            $state[$locale]['members'] = SiteSetting::getJson("home.$locale.members");
        }

        $this->form->fill($state);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('فارسی — اسلایدرِ قهرمان')
                    ->schema(self::heroFields('fa')),
                Section::make('فارسی — ردیفِ ویدیو')
                    ->schema(self::videoFields('fa'))
                    ->collapsed(),
                Section::make('فارسی — بخشِ اپلیکیشن')
                    ->schema(self::appFields('fa'))
                    ->collapsed(),
                Section::make('فارسی — بخشِ دوره‌ها')
                    ->schema(self::coursesFields('fa'))
                    ->collapsed(),
                Section::make('فارسی — نتایجِ اعضا')
                    ->schema(self::membersFields('fa'))
                    ->collapsed(),
                Section::make('فارسی — ویترینِ اینستاگرام')
                    ->description('پست/ریلِ امبدشده‌ی اینستاگرام که کنارِ متنِ صفحه‌ی خانه نمایش داده می‌شود.')
                    ->schema(self::instaShowcaseFields('fa', 'insta_showcase', 'insta_embed_url', includeProfileUrl: true))
                    ->collapsed(),
                Section::make('فارسی — ویترینِ اینستاگرام — ردیفِ دوم (اختیاری)')
                    ->description('یک ردیفِ دومِ اختیاریِ اینستاگرام زیرِ ردیفِ اول — برای پست، ریل یا حسابِ دیگر. یک آدرسِ امبد یا تصویرِ جایگزین اضافه کنید تا ظاهر شود.')
                    ->schema(self::instaShowcaseFields('fa', 'insta_showcase2', alwaysVisible: false))
                    ->collapsed(),

                Section::make('انگلیسی — اسلایدرِ قهرمان')
                    ->schema(self::heroFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — ردیفِ ویدیو')
                    ->schema(self::videoFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — بخشِ اپلیکیشن')
                    ->schema(self::appFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — بخشِ دوره‌ها')
                    ->schema(self::coursesFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — نتایجِ اعضا')
                    ->schema(self::membersFields('en'))
                    ->collapsed(),
                Section::make('انگلیسی — ویترینِ اینستاگرام')
                    ->description('پست/ریلِ امبدشده‌ی اینستاگرام که کنارِ متنِ صفحه‌ی خانه نمایش داده می‌شود.')
                    ->schema(self::instaShowcaseFields('en', 'insta_showcase', 'insta_embed_url', includeProfileUrl: true))
                    ->collapsed(),
                Section::make('انگلیسی — ویترینِ اینستاگرام — ردیفِ دوم (اختیاری)')
                    ->description('یک ردیفِ دومِ اختیاریِ اینستاگرام زیرِ ردیفِ اول — برای پست، ریل یا حسابِ دیگر. یک آدرسِ امبد یا تصویرِ جایگزین اضافه کنید تا ظاهر شود.')
                    ->schema(self::instaShowcaseFields('en', 'insta_showcase2', alwaysVisible: false))
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

    private static function videoUpload(string $key): FileUpload
    {
        return FileUpload::make($key)
            ->disk('public')
            ->directory('site-settings')
            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime'])
            ->nullable();
    }

    private static function heroFields(string $l): array
    {
        $fields = [];
        foreach ([1, 2, 3] as $i) {
            $fields[] = TextInput::make("$l.hero{$i}_title")->label("اسلاید $i — عنوان");
            $fields[] = Textarea::make("$l.hero{$i}_sub")->label("اسلاید $i — زیرعنوان")->rows(2);
            $fields[] = self::imageUpload("$l.hero{$i}_image")
                ->label("اسلاید $i — تصویرِ پس‌زمینه");
        }

        return $fields;
    }

    private static function videoFields(string $l): array
    {
        $fields = [];
        foreach ([1, 2, 3] as $i) {
            $fields[] = TextInput::make("$l.video{$i}_caption")->label("ویدیو $i — عنوان");
            $fields[] = self::imageUpload("$l.video{$i}_thumb")
                ->label("ویدیو $i — عکسِ تامبنیل");
            $fields[] = TextInput::make("$l.video{$i}_embed")
                ->label("ویدیو $i — آدرسِ امبد (یوتیوب / ویمیو / اینستاگرام / تیک‌تاک)")
                ->helperText('یک لینکِ یوتیوب، ویمیو، اینستاگرام یا تیک‌تاک اینجا بگذارید، یا فایل را زیر آپلود کنید — نه هر دو.');
            $fields[] = self::videoUpload("$l.video{$i}_file")
                ->label("ویدیو $i — فایل (mp4)")
                ->helperText('یک ویدیو آپلود کنید. تا ۱۲۸ مگابایت.');
        }

        return $fields;
    }

    private static function appFields(string $l): array
    {
        return [
            TextInput::make("$l.app_title")->label('عنوان'),
            TextInput::make("$l.app_subtitle")->label('زیرعنوان'),
            Textarea::make("$l.app_text")->label('متن')->rows(4),
            TextInput::make("$l.app_button_label")->label('متنِ دکمه'),
            self::imageUpload("$l.app_image")
                ->label('تصویرِ بخش'),
        ];
    }

    private static function coursesFields(string $l): array
    {
        $fields = [
            TextInput::make("$l.courses_title")->label('عنوانِ بخش'),
            Textarea::make("$l.courses_subtitle")->label('زیرعنوانِ بخش')->rows(2),
        ];
        $blogPath = $l === 'en' ? '/en/blog' : '/blog';
        foreach ([1, 2, 3] as $i) {
            $fields[] = TextInput::make("$l.course{$i}_label")->label("دوره $i — برچسب");
            $fields[] = TextInput::make("$l.course{$i}_link")
                ->label("دوره $i — لینک")
                ->nullable()
                ->helperText("مقصدِ این کارت هنگامِ کلیک — مسیرِ نسبی (مثل /blog/some-article) یا آدرسِ کاملِ https://. برای لینک به وبلاگ ($blogPath) خالی بگذارید.");
            $fields[] = self::imageUpload("$l.course{$i}_image")
                ->label("دوره $i — تصویر");
        }

        return $fields;
    }

    private static function membersFields(string $l): array
    {
        return [
            TextInput::make("$l.members_title")->label('عنوانِ بخش'),
            Textarea::make("$l.members_subtitle")->label('زیرعنوانِ بخش')->rows(2),
            TextInput::make("$l.members_button_label")->label('متنِ دکمه'),
            Repeater::make("$l.members")
                ->label('اعضا')
                ->schema([
                    TextInput::make('name')->label('نام'),
                    self::imageUpload('photo')
                        ->label('عکس'),
                    TextInput::make('video_embed')
                        ->label('آدرسِ امبدِ ویدیو (یوتیوب)')
                        ->helperText('یک لینکِ یوتیوب اینجا بگذارید، یا فایلِ ویدیو را زیر انتخاب کنید — نه هر دو.')
                        ->nullable(),
                    self::videoUpload('video_file')
                        ->label('فایلِ ویدیو (mp4)')
                        ->helperText('یک ویدیو آپلود کنید. تا ۱۲۸ مگابایت.'),
                ])
                ->defaultItems(0)
                ->addActionLabel('افزودنِ عضو'),
        ];
    }

    // یک ردیف کامل ویترین اینستاگرام — با پیشوند $prefix برای ردیف اول (insta_showcase) یا
    // ردیف دوم (insta_showcase2) قابل استفادهٔ مجدد است. کلید آدرس embed ردیف اول به‌صورت تاریخی
    // insta_embed_url است — باید صریحاً به‌عنوان $embedKey پاس شود. $includeProfileUrl فقط برای
    // ردیف اول true است تا فیلد مشترک insta_url (لینک پروفایل) یک‌بار نمایش داده شود.
    private static function instaShowcaseFields(string $l, string $prefix = 'insta_showcase', ?string $embedKey = null, bool $alwaysVisible = true, bool $includeProfileUrl = false): array
    {
        $embedKey ??= "{$prefix}_embed_url";

        $fields = [];

        if ($includeProfileUrl) {
            $fields[] = TextInput::make("$l.insta_url")
                ->label('لینکِ پروفایلِ اینستاگرام')
                ->helperText('پروفایلِ اینستاگرامِ شما، مثلاً https://instagram.com/ehsandibazar — هرگاه ردیفی آدرسِ دکمه‌ی خودش را نداشته باشد برای دکمه‌های «دنبال‌کردن» استفاده می‌شود. (لینکِ مشترکِ هر دو ردیف.)');
        }

        $fields[] = Toggle::make("$l.{$prefix}_enabled")
            ->label('فعال‌کردنِ ویترینِ اینستاگرام')
            ->helperText($alwaysVisible
                ? 'لازم نیست — این ردیف همیشه نمایش داده می‌شود. برای نمایشِ پست/ریلِ زنده یک آدرسِ امبد، یا برای عکسِ ثابت یک تصویرِ جایگزین زیر اضافه کنید.'
                : 'اگر آدرسِ امبد یا تصویرِ جایگزین زیر اضافه کنید لازم نیست — این ردیف آن‌وقت خودکار نمایش داده می‌شود. فقط اگر می‌خواهید ردیف تنها با آیکونِ پیش‌فرضِ اینستاگرام نمایش داده شود این را روشن کنید.');
        $fields[] = TextInput::make("$l.$embedKey")
            ->label('آدرسِ امبدِ اینستاگرام')
            ->url()
            ->helperText('هر آدرسِ پست یا ریلِ عمومیِ اینستاگرام را بگذارید، مثلاً https://www.instagram.com/p/ABC123/ — پس از ذخیره خودکار نمایش داده می‌شود.');

        return array_merge($fields, [
            TextInput::make("$l.{$prefix}_title")->label('عنوانِ بخش'),
            TextInput::make("$l.{$prefix}_subtitle")->label('زیرعنوانِ بخش'),
            TextInput::make("$l.{$prefix}_button_text")
                ->label('متنِ دکمه')
                ->helperText('برای حفظِ پیش‌فرضِ «ما را در اینستاگرام دنبال کنید» خالی بگذارید.'),
            TextInput::make("$l.{$prefix}_button_url")
                ->label('آدرسِ دکمه')
                ->url()
                ->helperText('برای استفاده از آدرسِ اینستاگرامِ بالا خالی بگذارید.'),
            self::imageUpload("$l.{$prefix}_fallback_image")
                ->label('تصویرِ جایگزین (اختیاری)')
                ->helperText('اگر امبدِ اینستاگرام غیرفعال باشد، آدرس نداشته باشد یا بارگذاری نشود نمایش داده می‌شود.'),
        ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach (['fa', 'en'] as $locale) {
            foreach (array_merge(self::TEXT_KEYS, self::FILE_KEYS) as $key) {
                $value = $state[$locale][$key] ?? null;

                // FileUpload گاهی مقدار را به‌صورت آرایه برمی‌گرداند —
                // اولین مسیر را برمی‌داریم تا در ستون متنی ذخیره‌شدنی باشد
                if (is_array($value)) {
                    $value = array_values(array_filter($value))[0] ?? null;
                }

                SiteSetting::set("home.$locale.$key", $value, 'homepage');
            }

            SiteSetting::set(
                "home.$locale.members",
                json_encode($state[$locale]['members'] ?? [], JSON_UNESCAPED_UNICODE),
                'homepage'
            );
        }

        Notification::make()
            ->success()
            ->title('تنظیماتِ صفحه‌ی خانه ذخیره شد')
            ->send();
    }
}

<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Model\Article;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

/**
 * فرمِ مقاله — نسخه‌ی فارسیِ فرمِ سایت انگلیسی، نگاشته به ستون‌های واقعیِ جدولِ زنده‌ی articles.
 * تفاوت‌های عمدیِ پل‌زننده: `lang` به‌جای locale، Toggleِ `status` (بولین) به‌جای enumِ رشته‌ای.
 * فیلدهای هنوز-منتقل‌نشده (tags/keywords/انتخابگرِ رسانه/افزونه‌ی RichContent) در ۴c/موتورِ محتوا می‌آیند.
 */
class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lang')
                    ->label('زبان')
                    ->options(['fa' => 'فارسی', 'en' => 'English'])
                    ->default('fa')
                    ->required(),

                TextInput::make('title')
                    ->label('عنوان')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        // فقط هنگام خالی‌بودنِ slug (ایجادِ تازه) خودکار پر می‌شود تا slugِ مقاله‌ی
                        // موجود (که در گوگل ایندکس شده) تصادفی عوض نشود. از نرمال‌سازیِ فارسی‌محور
                        // استفاده می‌کنیم (نه Str::slug که فارسی را به لاتین ترانسلیت می‌کند).
                        if (blank($get('slug'))) {
                            $set('slug', \App\Services\Content\ContentDraftFactory::normalizeSlug((string) ($state ?? '')));
                        }
                    }),

                TextInput::make('slug')
                    ->label('اسلاگ (آدرس)')
                    ->required()
                    ->helperText('بخشِ آخرِ آدرسِ مقاله. برای مقاله‌های موجود آن را تغییر ندهید (ریسکِ سئو).'),

                Select::make('translation_of')
                    ->label('ترجمه‌ی مقاله‌ی')
                    ->options(fn () => Article::query()->orderByDesc('id')->limit(200)->pluck('title', 'id'))
                    ->searchable()
                    ->nullable()
                    ->helperText('اختیاری — اگر این نسخه‌ی زبانِ دیگرِ یک مقاله‌ی موجود است، آن را اینجا انتخاب کنید.'),

                // دسته‌بندی — به رابطه‌ی categories() (morphToMany روی categorizables) وصل است و فقط
                // دسته‌های نوعِ مقاله (type=Article، فعال) را نشان می‌دهد. همان دسته‌هایی که storefront
                // (/category/…) و فهرستِ مقاله‌ها از آن استفاده می‌کنند — بدونِ تغییرِ جدول.
                Select::make('categories')
                    ->label('دسته‌بندی')
                    ->relationship('categories', 'title', fn ($query) => $query
                        ->where('type', Article::class)
                        ->where('status', 1)
                        ->orderBy('sorting'))
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->helperText('دسته‌های مقاله که روی سایت و صفحه‌ی /category نمایش داده می‌شوند.'),

                // فیلدِ برچسب — به رابطه‌ی موجودِ tags() (morphToMany روی taggables) وصل است، نه به
                // کلاسِ خاص. وقتی در موج ۵ MorphMap فعال و جدولِ tags همگرا شود، این کد بدونِ بازنویسی
                // کار می‌کند (حداکثر 'title' به 'name' تغییر می‌کند). صفر تغییرِ جدول/URL الان.
                Select::make('tags')
                    ->label('برچسب‌ها')
                    ->relationship('tags', 'title')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->helperText('برای سازمان‌دهی و صفحه‌ی /article/tag. از برچسب‌های موجود انتخاب کنید یا جدید بسازید.')
                    ->createOptionForm([
                        TextInput::make('title')->label('نامِ برچسب')->required(),
                        Toggle::make('status')->label('فعال')->default(true),
                    ]),

                Textarea::make('excerpt')
                    ->label('خلاصه')
                    ->rows(3)
                    ->nullable()
                    ->helperText('متنِ کوتاهی که روی کارتِ مقاله در فهرست نمایش داده می‌شود.'),

                RichEditor::make('body')
                    ->label('متنِ مقاله')
                    ->required()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('articles/inline')
                    ->plugins([\App\Filament\RichContent\MediaLibraryRichContentPlugin::make('articles/inline')])
                    ->columnSpanFull(),

                // انتخابگرِ رسانه — به‌جای آپلودِ ساده، تصویر را از کتابخانه‌ی رسانه انتخاب می‌کند
                // (یا همان‌جا آپلود می‌کند). مقدارِ ذخیره‌شده همان رشته‌ی disk_path است — عیناً همان
                // چیزی که FileUpload ذخیره می‌کرد — پس هر ردیفِ موجود و storefront بدونِ تغییر کار می‌کند.
                \App\Filament\Forms\Components\MediaPickerInput::make('image_path')
                    ->label('تصویرِ شاخص')
                    ->onlyImages()
                    ->uploadDirectory('articles')
                    ->nullable()
                    // مقاله‌های قدیمی تصویرِ شاخص را در رابطه‌ی image() (مسیرِ public/uploads) دارند نه در
                    // ستونِ image_path؛ این فقط برای «نمایشِ» همان تصویرِ فعلی در فرم است — چیزی ذخیره
                    // یا در storefront تغییر داده نمی‌شود (storefront همیشه اول رابطه‌ی image را می‌خواند).
                    ->fallbackPreviewUrl(function (?Article $record): ?string {
                        // دقیقاً همان آدرسی که storefront برای هیرو می‌سازد: url($image->url) —
                        // اکسسورِ Image::url خودش APP_URL (شاملِ /public روی production) را جلو می‌گذارد،
                        // پس پیش‌نمایشِ فرم هم مثلِ سایت درست باز می‌شود. (قبلاً از مسیرِ خام url($raw)
                        // استفاده می‌شد که روی ساختارِ /public + storage/app/public تولید نمی‌شد.)
                        $img = $record?->image->first();

                        return $img ? url($img->url) : null;
                    })
                    ->helperText('از کتابخانه‌ی رسانه انتخاب کنید یا یک تصویرِ تازه آپلود کنید — WebP، تامبنیل و اندازه‌های ریسپانسیو خودکار ساخته می‌شوند.'),

                TextInput::make('image_alt')
                    ->label('متنِ جایگزینِ تصویر (ALT)')
                    ->maxLength(255)
                    ->nullable()
                    ->helperText('توضیحِ تصویر برای گوگل و صفحه‌خوان‌ها؛ خالی بماند، از عنوانِ مقاله استفاده می‌شود.'),

                TextInput::make('author_name')
                    ->label('نویسنده')
                    ->default('احسان دیبازر')
                    ->nullable(),

                TextInput::make('reading_time')
                    ->label('زمانِ مطالعه (دقیقه)')
                    ->numeric()
                    ->nullable(),

                Section::make('سئو و پیش‌نمایشِ اشتراک‌گذاری (اختیاری)')
                    ->description('خالی بماند، به‌طور خودکار از عنوان/خلاصه استفاده می‌شود. فقط اگر عبارتِ متفاوتی برای گوگل یا شبکه‌های اجتماعی می‌خواهید پر کنید.')
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('seo_title')->label('عنوانِ سئو')->maxLength(70)->nullable(),
                        Textarea::make('meta_description')->label('توضیحاتِ متا')->rows(2)->maxLength(160)->nullable(),
                        TextInput::make('og_title')->label('عنوانِ اشتراک‌گذاری')->maxLength(70)->nullable(),
                        Textarea::make('og_description')->label('توضیحِ اشتراک‌گذاری')->rows(2)->maxLength(160)->nullable(),
                    ]),

                Repeater::make('faqs')
                    ->label('پرسش‌های متداول (اختیاری)')
                    ->helperText('جفت‌های پرسش و پاسخ که پایینِ مقاله نمایش داده می‌شوند. به دیده‌شدنِ مقاله در گوگل هم کمک می‌کند.')
                    ->schema([
                        TextInput::make('question')->label('پرسش')->required(),
                        Textarea::make('answer')->label('پاسخ')->rows(3)->required(),
                    ])
                    ->addActionLabel('افزودنِ پرسش')
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                    ->defaultItems(0)
                    ->nullable()
                    ->columnSpanFull(),

                Section::make('پرامپت‌های تصویرِ هوش مصنوعی (اختیاری)')
                    ->description('برای موتورِ تصویرِ هوش مصنوعی (گروه‌های بعد). فعلاً فقط ذخیره می‌شوند.')
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('hero_image_prompt')->label('پرامپتِ تصویرِ اصلی')->rows(2)->nullable(),
                        Textarea::make('thumbnail_image_prompt')->label('پرامپتِ تامبنیل')->rows(2)->nullable(),
                        Textarea::make('og_image_prompt')->label('پرامپتِ تصویرِ Open Graph')->rows(2)->nullable(),
                        Textarea::make('social_image_prompt')->label('پرامپتِ تصویرِ اجتماعی')->rows(2)->nullable(),
                    ]),

                // وضعیتِ انتشار — فیلدِ مجازیِ publish_state (نه ستونِ DB) که در صفحاتِ Create/Edit به
                // status(بولین)/is_scheduled/published_at ترجمه می‌شود و به موتورِ زمان‌بندیِ خودکار
                // (articles:publish-due) وصل است.
                Select::make('publish_state')
                    ->label('وضعیتِ انتشار')
                    ->options([
                        'draft' => 'پیش‌نویس (نمایش داده نمی‌شود)',
                        'scheduled' => 'زمان‌بندی‌شده (در تاریخِ انتشار خودکار منتشر می‌شود)',
                        'published' => 'منتشرشده (همین حالا روی سایت)',
                    ])
                    ->default('draft')
                    ->live()
                    ->required(),

                DateTimePicker::make('published_at')
                    ->label('تاریخِ انتشار')
                    ->seconds(false)
                    ->visible(fn (Get $get): bool => in_array($get('publish_state'), ['scheduled', 'published'], true))
                    ->required(fn (Get $get): bool => $get('publish_state') === 'scheduled')
                    ->minDate(fn (Get $get) => $get('publish_state') === 'scheduled' ? now() : null)
                    ->helperText('برای «زمان‌بندی‌شده» یک تاریخِ آینده بگذارید — سیستم در همان لحظه خودکار منتشر می‌کند.'),
            ]);
    }
}

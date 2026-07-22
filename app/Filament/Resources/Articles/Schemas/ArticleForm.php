<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Model\Article;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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
                    ->options(['fa' => 'فارسی', 'en' => 'English', 'tr' => 'Türkçe'])
                    ->default('fa')
                    ->required(),

                TextInput::make('title')
                    ->label('عنوان')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        // فقط هنگام خالی‌بودنِ slug (ایجادِ تازه) خودکار پر می‌شود تا slugِ مقاله‌ی
                        // موجود (که در گوگل ایندکس شده) تصادفی عوض نشود.
                        if (blank($get('slug'))) {
                            $set('slug', Str::slug($state ?? ''));
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
                    ->columnSpanFull(),

                FileUpload::make('image_path')
                    ->label('تصویرِ شاخص')
                    ->image()
                    ->disk('public')
                    ->directory('articles')
                    ->nullable()
                    ->helperText('تصویرِ اصلیِ مقاله. (اتصالِ کاملِ کتابخانه‌ی رسانه و تولیدِ WebP در گروه بعد.)'),

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

                // پلِ انتشار: Toggleِ status (بولینِ زنده). روشن = 1 = روی سایت دیده می‌شود (همان
                // where('status',1)ی storefront/sitemap). خاموش = 0 = پیش‌نویس.
                Toggle::make('status')
                    ->label('منتشر شده')
                    ->helperText('روشن: روی سایت نمایش داده می‌شود. خاموش: پیش‌نویس.')
                    ->default(false),

                DateTimePicker::make('published_at')
                    ->label('تاریخِ انتشار')
                    ->nullable()
                    ->helperText('اختیاری — فعلاً فقط ذخیره/نمایش می‌شود.'),
            ]);
    }
}

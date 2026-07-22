<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Model\Page;
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
 * فرمِ صفحه — نسخه‌ی فارسیِ فرمِ Pageِ سایت انگلیسی، نگاشته به ستون‌های جدولِ زنده‌ی pages.
 * همان پل‌های Article: `lang` و Toggleِ `status` بولین. storefront (`/page/{slug}`) دست‌نخورده.
 */
class PageForm
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
                        if (blank($get('slug'))) {
                            $set('slug', Str::slug($state ?? ''));
                        }
                    }),

                TextInput::make('slug')
                    ->label('اسلاگ (آدرس)')
                    ->required()
                    ->helperText('بخشِ آخرِ آدرسِ صفحه. برای صفحه‌های موجود آن را تغییر ندهید (ریسکِ سئو).'),

                Select::make('translation_of')
                    ->label('ترجمه‌ی صفحه‌ی')
                    ->options(fn () => Page::query()->orderByDesc('id')->limit(200)->pluck('title', 'id'))
                    ->searchable()
                    ->nullable(),

                // برچسب — روی رابطه‌ی موجودِ tags() (polymorphic). سازگار با MorphMapِ آینده بدونِ بازنویسی.
                Select::make('tags')
                    ->label('برچسب‌ها')
                    ->relationship('tags', 'title')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('title')->label('نامِ برچسب')->required(),
                        Toggle::make('status')->label('فعال')->default(true),
                    ]),

                RichEditor::make('body')
                    ->label('متنِ صفحه')
                    ->required()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('pages/inline')
                    ->columnSpanFull(),

                FileUpload::make('image_path')
                    ->label('تصویرِ شاخص')
                    ->image()
                    ->disk('public')
                    ->directory('pages')
                    ->nullable(),

                TextInput::make('image_alt')
                    ->label('متنِ جایگزینِ تصویر (ALT)')
                    ->maxLength(255)
                    ->nullable(),

                Section::make('سئو و پیش‌نمایشِ اشتراک‌گذاری (اختیاری)')
                    ->description('خالی بماند، به‌طور خودکار از عنوان استفاده می‌شود.')
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('seo_title')->label('عنوانِ سئو')->maxLength(70)->nullable(),
                        Textarea::make('meta_description')->label('توضیحاتِ متا')->rows(2)->maxLength(160)->nullable(),
                        TextInput::make('meta_keywords')->label('کلیدواژه‌های متا')->nullable(),
                        TextInput::make('canonical_url')->label('آدرسِ canonical')->nullable()
                            ->helperText('خالی بماند تا خودِ آدرسِ صفحه canonical باشد.'),
                        TextInput::make('robots')->label('robots')->nullable()
                            ->helperText('خالی = index,follow. فقط اگر می‌خواهید صفحه ایندکس نشود پر کنید.'),
                        TextInput::make('og_title')->label('عنوانِ اشتراک‌گذاری')->maxLength(70)->nullable(),
                        Textarea::make('og_description')->label('توضیحِ اشتراک‌گذاری')->rows(2)->maxLength(160)->nullable(),
                    ]),

                Repeater::make('faqs')
                    ->label('پرسش‌های متداول (اختیاری)')
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

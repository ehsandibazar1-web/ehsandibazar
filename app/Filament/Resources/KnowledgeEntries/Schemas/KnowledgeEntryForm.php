<?php

namespace App\Filament\Resources\KnowledgeEntries\Schemas;

use App\Models\KnowledgeEntry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

// دسته‌بندی‌های پیشنهادی — فقط پیشنهاد (datalist)، نه یک enum بسته؛ ادمین می‌تواند هر متنِ
// دلخواهِ دیگری هم تایپ کند.
class KnowledgeEntryForm
{
    private const SUGGESTED_CATEGORIES = [
        'بیوگرافی', 'خدمات', 'سیاست‌ها', 'دوره‌ها', 'هنرهای رزمی', 'مکان‌ها',
        'پرسش‌های متداول', 'محصولات', 'اطلاعاتِ کسب‌وکار', 'اطلاعاتِ تماس', 'روش‌های تمرین',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('عنوان')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('category')
                    ->label('دسته‌بندی')
                    ->required()
                    ->datalist(self::SUGGESTED_CATEGORIES)
                    ->helperText('یک پیشنهاد را انتخاب کنید یا متنِ دلخواهِ خود را تایپ کنید — مثلاً بیوگرافی، خدمات، مکان‌ها، روش‌های تمرین.'),

                Select::make('locale')
                    ->label('زبان')
                    ->options(['fa' => 'فارسی', 'en' => 'English'])
                    ->default('fa')
                    ->required()
                    ->helperText('این ورودی به کدام زبان نوشته شده — فقط برای محتوای همان زبان استفاده می‌شود.'),

                Textarea::make('content')
                    ->label('محتوا')
                    ->required()
                    ->rows(8)
                    ->columnSpanFull()
                    ->helperText('خودِ واقعیت/دانش — دستیارِ هوشِ مصنوعی وقتی این ورودی را مرتبط تشخیص دهد، آن را می‌خواند.'),

                TextInput::make('source')
                    ->label('منبع')
                    ->nullable()
                    ->columnSpanFull()
                    ->helperText('اختیاری — این دانش از کجا آمده (یک سند، یک گفت‌وگو، یک URL) برای مرجعِ خودتان.'),

                Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        KnowledgeEntry::STATUS_DRAFT => 'پیش‌نویس',
                        KnowledgeEntry::STATUS_ACTIVE => 'فعال',
                        KnowledgeEntry::STATUS_ARCHIVED => 'بایگانی‌شده',
                    ])
                    ->default(KnowledgeEntry::STATUS_ACTIVE)
                    ->required()
                    ->helperText('فقط ورودی‌های «فعال» توسط دستیارِ هوشِ مصنوعی استفاده می‌شوند.'),

                Select::make('priority')
                    ->label('اولویت')
                    ->options([
                        KnowledgeEntry::PRIORITY_LOW => 'کم',
                        KnowledgeEntry::PRIORITY_MEDIUM => 'متوسط',
                        KnowledgeEntry::PRIORITY_HIGH => 'زیاد',
                        KnowledgeEntry::PRIORITY_CRITICAL => 'حیاتی',
                    ])
                    ->default(KnowledgeEntry::PRIORITY_MEDIUM)
                    ->required()
                    ->helperText('ورودی‌های با اولویتِ بالاتر هنگامِ انتخابِ واقعیت‌ها توسط هوشِ مصنوعی ترجیح داده می‌شوند.'),

                Toggle::make('is_pinned')
                    ->label('همیشه لحاظ شود')
                    ->helperText('ورودی‌های سنجاق‌شده صرف‌نظر از ارتباطِ موضوعی همیشه به هوشِ مصنوعی داده می‌شوند — برای واقعیت‌های حتماً‌دانستنی (مثلِ نامِ کسب‌وکار، سیاست‌های اصلی).'),

                DateTimePicker::make('expires_at')
                    ->label('تاریخِ انقضا')
                    ->nullable()
                    ->helperText('اختیاری — پس از این تاریخ/ساعت، این ورودی به‌طور خودکار از تولیدِ هوشِ مصنوعی کنار گذاشته می‌شود (مثلِ یک تخفیفِ فصلی).'),

                Select::make('tags')
                    ->relationship('tags', 'title')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->label('تگ‌ها')
                    ->columnSpanFull()
                    ->helperText('اختیاری — تطبیقِ کلمه‌ای را هنگامِ انتخابِ ورودی‌های مرتبط بهتر می‌کند.')
                    ->createOptionForm([
                        TextInput::make('title')
                            ->label('نامِ تگ')
                            ->required(),
                    ]),

                Repeater::make('attachments')
                    ->relationship()
                    ->label('پیوست‌های موجود')
                    ->schema([
                        TextInput::make('original_filename')
                            ->label('فایل')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->addable(false)
                    ->deletable()
                    ->reorderable(false)
                    ->collapsed()
                    ->itemLabel(fn (array $state): ?string => $state['original_filename'] ?? null)
                    ->columnSpanFull()
                    ->visibleOn('edit'),

                FileUpload::make('new_attachments')
                    ->label('افزودنِ پیوست')
                    ->multiple()
                    ->disk('public')
                    ->directory('knowledge')
                    ->preserveFilenames()
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'text/html', 'text/markdown'])
                    ->columnSpanFull()
                    ->dehydrated()
                    ->helperText('آپلودِ PDF، سندِ Word، TXT، HTML یا Markdown برای مرجعِ هوشِ مصنوعی/ادمین — تصاویر به کتابخانه‌ی رسانه تعلق دارند. توجه: استخراج و ایندکسِ برداری فعلاً غیرفعال است؛ فایل ذخیره می‌شود ولی هنوز embed نمی‌شود.'),

                TextInput::make('new_website_url')
                    ->label('یا افزودنِ یک صفحه‌ی وب با URL')
                    ->url()
                    ->nullable()
                    ->columnSpanFull()
                    ->dehydrated()
                    ->helperText('آدرسِ صفحه ذخیره می‌شود. توجه: واکشی/استخراج/ایندکسِ برداری فعلاً غیرفعال است.'),
            ]);
    }
}

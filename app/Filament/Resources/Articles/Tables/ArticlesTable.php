<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Model\Article;
use App\Services\ArticleImport\ArticleRoundtripExporter;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // eager-load رابطه‌ی image تا هر ردیف کوئریِ جدا نزند (N+1).
            ->modifyQueryUsing(fn ($query) => $query->with('image'))
            ->columns([
                // تصویرِ هیرو: اولویت با رابطه‌ی زنده‌ی image() (همان که storefront نشان می‌دهد)؛
                // اگر نبود، fallback به ستونِ image_path (مقاله‌های ایمپورت‌شده‌ی جدید).
                ImageColumn::make('hero_image')
                    ->label('')
                    ->height(40)
                    ->square()
                    ->getStateUsing(function (Article $record): ?string {
                        // ۱) image_path روی دیسکِ public — فقط اگر فایلش واقعاً موجود باشد.
                        if (filled($record->image_path)
                            && \Illuminate\Support\Facades\Storage::disk('public')->exists($record->image_path)) {
                            return \Illuminate\Support\Facades\Storage::disk('public')->url($record->image_path);
                        }
                        // ۲) رابطه‌ی زنده‌ی image() — همان آدرسی که storefront می‌سازد: url($image->url)
                        // (اکسسورِ Image::url خودش APP_URL شاملِ /public را جلو می‌گذارد). قبلاً از مسیرِ
                        // خام url($raw) استفاده می‌شد که روی ساختارِ production تولید نمی‌شد و عکسِ شکسته می‌داد.
                        $img = $record->image->first();

                        return $img ? url($img->url) : null;
                    }),

                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),

                TextColumn::make('lang')
                    ->label('زبان')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->state(fn (Article $record): string => (int) $record->status === 1
                        ? 'منتشرشده'
                        : ($record->is_scheduled ? 'زمان‌بندی‌شده' : 'پیش‌نویس'))
                    ->color(fn (Article $record): string => (int) $record->status === 1
                        ? 'success'
                        : ($record->is_scheduled ? 'warning' : 'gray')),

                TextColumn::make('published_at')
                    ->label('انتشار')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('viewCount')
                    ->label('بازدید')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                // accessorِ getCreatedAtAttributeِ legacy مقدار را شمسی می‌کند؛ مقدارِ خام را می‌خوانیم.
                TextColumn::make('created')
                    ->label('تاریخ')
                    ->state(fn ($record) => ($raw = $record->getRawOriginal('created_at'))
                        ? Carbon::parse($raw)->format('Y-m-d')
                        : '—'),

                TextColumn::make('updated_at')
                    ->label('آخرین ویرایش')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->label('وضعیت')
                    ->placeholder('همه')
                    ->trueLabel('منتشرشده')
                    ->falseLabel('پیش‌نویس'),

                SelectFilter::make('lang')
                    ->label('زبان')
                    ->options(['fa' => 'فارسی', 'en' => 'English']),

                // مقاله‌های فارسی که هنوز نسخه‌ی انگلیسی ندارند (کاندیدِ ترجمه).
                Filter::make('needs_en_translation')
                    ->label('فارسیِ بدونِ ترجمه‌ی انگلیسی')
                    ->query(fn ($query) => $query
                        ->where('lang', 'fa')
                        ->whereDoesntHave('translations', fn ($q) => $q->where('lang', 'en'))),

                // مقاله‌های منتشرشده‌ی بدونِ توضیحاتِ متا (شکافِ سئو — برای گوگل مهم است).
                Filter::make('missing_meta_description')
                    ->label('منتشرشده‌ی بدونِ توضیحاتِ متا')
                    ->query(fn ($query) => $query
                        ->where('status', 1)
                        ->where(fn ($q) => $q->whereNull('meta_description')->orWhere('meta_description', ''))),
            ])
            ->recordActions([
                self::previewAction(),
                self::exportForAiAction(),
                self::duplicateAction(),
                self::cloneTranslationAction(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    self::scheduleBulkAction(),
                    self::cancelScheduleBulkAction(),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    // نمایش/پیش‌نمایش روی سایت — با «لینکِ امضاشده‌ی موقت» تا پیش‌نویس/زمان‌بندی‌شده هم (که روی
    // storefront عادی ۴۰۴ می‌دهند) قابلِ دیدن باشند. لینک ۳۰ دقیقه معتبر است.
    private static function previewAction(): Action
    {
        return Action::make('preview')
            ->label('نمایش')
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->url(fn (Article $record): string => (int) $record->status === 1
                ? url('article/'.$record->slug)
                : URL::temporarySignedRoute('site.article', now()->addMinutes(30), ['slug' => $record->slug, 'preview' => 1]))
            ->openUrlInNewTab();
    }

    // تکثیرِ مقاله در همان زبان → یک پیش‌نویسِ جدید (بدونِ بازدید/تاریخِ انتشار).
    private static function duplicateAction(): Action
    {
        return Action::make('duplicate')
            ->label('تکثیر')
            ->icon('heroicon-o-document-duplicate')
            ->color('gray')
            ->requiresConfirmation()
            ->action(function (Article $record): void {
                $copy = $record->replicate(['viewCount']);
                $copy->title = $record->title.' (کپی)';
                $copy->slug = $record->slug.'-copy-'.Str::lower(Str::random(4));
                $copy->status = 0;
                $copy->published_at = null;
                $copy->translation_of = null;
                $copy->viewCount = 0;
                $copy->save();

                Notification::make()->success()->title('مقاله به‌عنوانِ پیش‌نویسِ جدید تکثیر شد')->send();
            });
    }

    // چرخه‌ی ویرایشِ AI: مقاله را به فرمتِ قابلِ‌فهمِ AI Import دانلود می‌کند (با idِ مخفی) تا بعد از
    // ویرایشِ AI، از همان صفحه‌ی AI Import دوباره وارد شود و «همین مقاله» آپدیت شود (نه درافتِ جدید).
    private static function exportForAiAction(): Action
    {
        return Action::make('exportForAi')
            ->label('دانلود برای ویرایشِ AI')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->modalHeading('دانلود برای ویرایشِ هوشِ مصنوعی')
            ->modalDescription('فایل شاملِ یک شناسه‌ی مخفی است؛ بعد از ویرایشِ AI، همین فایل را در صفحه‌ی «AI Import» بارگذاری کنید تا همین مقاله به‌روزرسانی شود. نشانیِ صفحه (slug) هرگز تغییر نمی‌کند.')
            ->schema([
                Select::make('format')
                    ->label('فرمت')
                    ->options(['json' => 'JSON (پیشنهادی — بدونِ افتِ داده)', 'markdown' => 'Markdown'])
                    ->default('json')
                    ->required(),
            ])
            ->action(function (Article $record, array $data) {
                $export = app(ArticleRoundtripExporter::class)->export($record, $data['format'] ?? 'json');

                return response()->streamDownload(
                    fn () => print ($export['content']),
                    $export['filename'],
                    ['Content-Type' => $export['mime'].'; charset=UTF-8'],
                );
            });
    }

    // کلون به زبانِ دیگر — متن عیناً کپی می‌شود و باید دستی ترجمه شود؛ به مقاله‌ی اصلی لینک می‌شود.
    private static function cloneTranslationAction(): Action
    {
        return Action::make('cloneTranslation')
            ->label(fn (Article $record): string => 'کلون به '.($record->lang === 'fa' ? 'English' : 'فارسی'))
            ->icon('heroicon-o-language')
            ->color('gray')
            ->requiresConfirmation()
            ->modalDescription('محتوا عیناً (بدونِ ترجمه) کپی می‌شود؛ یک پیش‌نویسِ جدید در زبانِ دیگر ساخته و به این مقاله لینک می‌شود — متنش را خودتان ترجمه کنید.')
            ->action(function (Article $record): void {
                $newLang = $record->lang === 'fa' ? 'en' : 'fa';

                $alreadyLinked = Article::query()
                    ->where('lang', $newLang)
                    ->where(function ($q) use ($record) {
                        $q->where('translation_of', $record->id)
                            ->orWhere('id', $record->translation_of);
                    })
                    ->exists();

                if ($alreadyLinked) {
                    Notification::make()->warning()->title('برای این مقاله از قبل یک ترجمه‌ی لینک‌شده وجود دارد')->send();

                    return;
                }

                $copy = $record->replicate(['viewCount']);
                $copy->lang = $newLang;
                $copy->slug = $record->slug.'-'.$newLang;
                $copy->status = 0;
                $copy->published_at = null;
                $copy->translation_of = $record->id;
                $copy->viewCount = 0;
                $copy->save();

                Notification::make()->success()->title('به‌عنوانِ پیش‌نویسِ '.strtoupper($newLang).' کلون شد — یادتان باشد متن را ترجمه کنید')->send();
            });
    }

    // زمان‌بندیِ گروهی — به هر مقاله‌ی انتخاب‌شده یک تاریخِ انتشارِ آینده می‌دهد و is_scheduled=true
    // می‌کند (status=0 می‌ماند تا دستورِ articles:publish-due در زمانِ مقرر منتشرش کند).
    private static function scheduleBulkAction(): BulkAction
    {
        return BulkAction::make('bulkSchedule')
            ->label('زمان‌بندیِ انتشار')
            ->icon('heroicon-o-calendar-days')
            ->color('warning')
            ->schema([
                Select::make('pattern')
                    ->label('الگو')
                    ->options([
                        'daily' => 'روزی یک مقاله',
                        'every_n_days' => 'هر چند روز یک مقاله',
                        'weekly' => 'هفته‌ای یک مقاله',
                        'specific_days' => 'فقط روزهای مشخصِ هفته',
                    ])
                    ->default('daily')
                    ->live()
                    ->required(),

                TextInput::make('interval_days')
                    ->label('هر چند روز؟')
                    ->numeric()
                    ->minValue(1)
                    ->default(2)
                    ->visible(fn (Get $get) => $get('pattern') === 'every_n_days')
                    ->required(fn (Get $get) => $get('pattern') === 'every_n_days'),

                CheckboxList::make('weekdays')
                    ->label('کدام روزها؟')
                    ->options([
                        6 => 'شنبه', 0 => 'یکشنبه', 1 => 'دوشنبه', 2 => 'سه‌شنبه',
                        3 => 'چهارشنبه', 4 => 'پنجشنبه', 5 => 'جمعه',
                    ])
                    ->columns(4)
                    ->visible(fn (Get $get) => $get('pattern') === 'specific_days')
                    ->required(fn (Get $get) => $get('pattern') === 'specific_days'),

                DatePicker::make('start_date')
                    ->label('تاریخِ شروع')
                    ->default(now()->addDay()->toDateString())
                    ->minDate(now()->toDateString())
                    ->required(),

                TimePicker::make('time')
                    ->label('ساعتِ انتشار')
                    ->seconds(false)
                    ->default('09:00')
                    ->required(),
            ])
            ->action(function (Collection $records, array $data): void {
                $dates = self::buildScheduleDates(
                    count: $records->count(),
                    pattern: $data['pattern'],
                    intervalDays: (int) ($data['interval_days'] ?? 1),
                    weekdays: $data['weekdays'] ?? [],
                    startDate: $data['start_date'],
                    time: $data['time'],
                );

                $records->values()->each(function (Article $article, int $i) use ($dates): void {
                    $article->update([
                        'status' => 0,
                        'is_scheduled' => true,
                        'published_at' => $dates[$i],
                    ]);
                });

                Notification::make()->success()
                    ->title('زمان‌بندی روی '.$records->count().' مقاله اعمال شد')
                    ->send();
            })
            ->deselectRecordsAfterCompletion();
    }

    // لغوِ زمان‌بندی — مقاله را به پیش‌نویسِ عادی برمی‌گرداند (is_scheduled=false, published_at=null).
    private static function cancelScheduleBulkAction(): BulkAction
    {
        return BulkAction::make('cancelSchedule')
            ->label('لغوِ زمان‌بندی (→ پیش‌نویس)')
            ->icon('heroicon-o-x-circle')
            ->color('gray')
            ->requiresConfirmation()
            ->action(function (Collection $records): void {
                $count = 0;
                foreach ($records as $article) {
                    if ($article->is_scheduled) {
                        $article->update(['is_scheduled' => false, 'published_at' => null]);
                        $count++;
                    }
                }

                Notification::make()->success()->title($count.' مقاله به پیش‌نویس برگشت')->send();
            })
            ->deselectRecordsAfterCompletion();
    }

    // محاسبه‌ی تاریخ‌های انتشار بر اساسِ الگو (روزانه/هر N روز/هفتگی/روزهای مشخص).
    private static function buildScheduleDates(int $count, string $pattern, int $intervalDays, array $weekdays, string $startDate, string $time): array
    {
        $dates = [];
        $cursor = Carbon::parse($startDate);

        if ($pattern === 'specific_days') {
            $weekdays = array_map('intval', $weekdays);
            while (count($dates) < $count) {
                if (in_array((int) $cursor->dayOfWeek, $weekdays, true)) {
                    $dates[] = self::combine($cursor, $time);
                }
                $cursor = $cursor->copy()->addDay();
            }

            return $dates;
        }

        $step = match ($pattern) {
            'weekly' => 7,
            'every_n_days' => max(1, $intervalDays),
            default => 1,
        };

        for ($i = 0; $i < $count; $i++) {
            $dates[] = self::combine($cursor->copy()->addDays($i * $step), $time);
        }

        return $dates;
    }

    private static function combine(Carbon $date, string $time): Carbon
    {
        [$hour, $minute] = array_pad(explode(':', $time), 2, '0');

        return $date->copy()->setTime((int) $hour, (int) $minute);
    }
}

<?php

namespace App\Filament\Resources\ImportLogs\Tables;

use App\Filament\Resources\Articles\ArticleResource;
use App\Model\Article;
use App\Models\ImportLog;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ImportLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('زمان')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                TextColumn::make('by')
                    ->label('توسط')
                    ->state(fn (ImportLog $record): string => $record->user->name ?? '—'),

                TextColumn::make('ai_provider')
                    ->label('ارائه‌دهنده‌ی AI')
                    ->badge()
                    ->color('gray')
                    ->default('—')
                    ->searchable(),

                TextColumn::make('format')
                    ->label('قالب')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->label('نتیجه')
                    ->badge()
                    ->formatStateUsing(fn (ImportLog $record): string => $record->isRolledBack()
                        ? 'بازگردانده‌شده'
                        : ($record->status === 'imported' ? 'ایمپورت‌شده' : 'ناموفق'))
                    ->color(fn (ImportLog $record): string => match (true) {
                        $record->isRolledBack() => 'warning',
                        $record->status === 'imported' => 'success',
                        default => 'danger',
                    }),

                TextColumn::make('article_title')
                    ->label('مقاله')
                    ->limit(40)
                    ->default('—')
                    ->searchable()
                    ->url(fn (ImportLog $record): ?string => $record->article_id
                        ? ArticleResource::getUrl('edit', ['record' => $record->article_id])
                        : null),

                TextColumn::make('locale')
                    ->label('زبان')
                    ->badge()
                    ->default('—'),

                TextColumn::make('faq_count')
                    ->label('FAQها')
                    ->toggleable(),

                TextColumn::make('image_count')
                    ->label('تصاویر')
                    ->toggleable(),

                TextColumn::make('rolled_back_at')
                    ->label('زمانِ بازگردانی')
                    ->dateTime('Y-m-d H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('نتیجه')
                    ->options([
                        'imported' => 'ایمپورت‌شده',
                        'failed' => 'ناموفق',
                    ]),

                SelectFilter::make('ai_provider')
                    ->label('ارائه‌دهنده‌ی AI')
                    ->options(fn (): array => ImportLog::query()->whereNotNull('ai_provider')
                        ->distinct()->pluck('ai_provider', 'ai_provider')->all()),

                SelectFilter::make('locale')
                    ->label('زبان')
                    ->options(['fa' => 'فارسی', 'en' => 'English']),

                SelectFilter::make('user_id')
                    ->label('کاربر')
                    ->relationship('user', 'name'),

                TernaryFilter::make('rolled_back')
                    ->label('بازگردانده‌شده')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('rolled_back_at'),
                        false: fn ($query) => $query->whereNull('rolled_back_at'),
                    ),

                Filter::make('created_between')
                    ->schema([
                        DatePicker::make('from')->label('از تاریخ'),
                        DatePicker::make('until')->label('تا تاریخ'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                        ->when($data['until'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d))),
            ])
            ->recordActions([
                self::validationReportAction(),
                self::rollbackAction(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    // گزارشِ اعتبارسنجی — خطاها/هشدارهای ذخیره‌شده‌ی همان اجرا (ستون‌های errors/warnings).
    private static function validationReportAction(): Action
    {
        return Action::make('validationReport')
            ->label('گزارش')
            ->icon('heroicon-o-document-magnifying-glass')
            ->color('gray')
            ->visible(fn (ImportLog $record): bool => ! empty($record->errors) || ! empty($record->warnings))
            ->modalHeading(fn (ImportLog $record): string => 'گزارشِ اعتبارسنجی — '.optional($record->created_at)->format('Y-m-d H:i'))
            ->modalContent(fn (ImportLog $record) => view('filament.ai-studio.validation-report', ['log' => $record]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('بستن');
    }

    // بازگردانی — مقاله‌ی ایمپورت‌شده soft-delete و لاگ علامت‌گذاری می‌شود (همان منطقِ AiImport::rollbackLog).
    private static function rollbackAction(): Action
    {
        return Action::make('rollback')
            ->label('بازگردانی')
            ->icon('heroicon-o-arrow-uturn-left')
            ->color('danger')
            ->visible(fn (ImportLog $record): bool => $record->canRollBack())
            ->requiresConfirmation()
            ->modalHeading('این ایمپورت بازگردانده شود؟')
            ->modalDescription(fn (ImportLog $record): string => 'مقاله‌ی «'.$record->article_title.'» از سایت حذف می‌شود. تصاویرِ دانلودشده در کتابخانه‌ی رسانه می‌مانند.')
            ->action(function (ImportLog $record): void {
                if (! $record->canRollBack()) {
                    Notification::make()->danger()->title('این ایمپورت قابلِ بازگردانی نیست')->send();

                    return;
                }

                if ($article = Article::find($record->article_id)) {
                    $article->delete();
                }

                $record->update([
                    'rolled_back_at' => now(),
                    'rolled_back_by' => auth()->id(),
                ]);

                Notification::make()->success()->title('ایمپورت بازگردانده شد')->send();
            });
    }
}

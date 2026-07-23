<?php

namespace App\Filament\Resources\KnowledgeEntries\Tables;

use App\Jobs\IndexKnowledgeContent;
use App\Model\Tag;
use App\Models\KnowledgeEntry;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class KnowledgeEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('category')
                    ->label('دسته‌بندی')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('locale')
                    ->label('زبان')
                    ->badge(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        KnowledgeEntry::STATUS_ACTIVE => 'success',
                        KnowledgeEntry::STATUS_DRAFT => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('priority')
                    ->label('اولویت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        KnowledgeEntry::PRIORITY_CRITICAL => 'danger',
                        KnowledgeEntry::PRIORITY_HIGH => 'warning',
                        default => 'gray',
                    }),

                IconColumn::make('is_pinned')
                    ->label('سنجاق‌شده')
                    ->boolean(),

                TextColumn::make('tags_count')
                    ->label('تگ‌ها')
                    ->counts('tags'),

                TextColumn::make('attachments_count')
                    ->label('فایل‌ها')
                    ->counts('attachments'),

                TextColumn::make('all_chunks_count')
                    ->label('قطعه‌های RAG')
                    ->counts('allChunks')
                    ->badge()
                    ->color(fn (?int $state): string => $state ? 'success' : 'gray')
                    ->tooltip('تعدادِ قطعه‌های برداریِ ایندکس‌شده — فعلاً همیشه ۰ است چون ایندکسِ برداری (embedding) غیرفعال است.'),

                TextColumn::make('expires_at')
                    ->label('انقضا')
                    ->dateTime('Y-m-d')
                    ->placeholder('—')
                    ->color(fn (?KnowledgeEntry $record): ?string => $record?->isExpired() ? 'danger' : null)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('آخرین ویرایش')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('locale')
                    ->label('زبان')
                    ->options(['fa' => 'فارسی', 'en' => 'English']),

                SelectFilter::make('category')
                    ->label('دسته‌بندی')
                    ->options(fn () => KnowledgeEntry::query()->whereNotNull('category')->distinct()->pluck('category', 'category')->all()),

                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        KnowledgeEntry::STATUS_DRAFT => 'پیش‌نویس',
                        KnowledgeEntry::STATUS_ACTIVE => 'فعال',
                        KnowledgeEntry::STATUS_ARCHIVED => 'بایگانی‌شده',
                    ]),

                SelectFilter::make('priority')
                    ->label('اولویت')
                    ->options([
                        KnowledgeEntry::PRIORITY_LOW => 'کم',
                        KnowledgeEntry::PRIORITY_MEDIUM => 'متوسط',
                        KnowledgeEntry::PRIORITY_HIGH => 'زیاد',
                        KnowledgeEntry::PRIORITY_CRITICAL => 'حیاتی',
                    ]),

                SelectFilter::make('tags')
                    ->label('تگ')
                    ->relationship('tags', 'title')
                    ->options(fn () => Tag::pluck('title', 'id')),

                TernaryFilter::make('is_pinned')
                    ->label('سنجاق‌شده'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('reindex')
                    ->label('ایندکسِ مجدد')
                    ->icon(Heroicon::OutlinedArrowPath)
                    ->color('gray')
                    ->action(function (KnowledgeEntry $record): void {
                        // جاب دیسپچ می‌شود ولی چون IndexingService یک stub است، یک no-op بی‌ضرر است.
                        dispatch(new IndexKnowledgeContent($record));

                        foreach ($record->attachments as $attachment) {
                            dispatch(new IndexKnowledgeContent($attachment));
                        }

                        Notification::make()
                            ->warning()
                            ->title('ایندکسِ برداری فعلاً غیرفعال است')
                            ->body('این ورودی صف شد، ولی چون embedding هنوز فعال نیست، قطعه‌ی برداری ساخته نمی‌شود.')
                            ->send();
                    }),
                DeleteAction::make(),
            ])
            ->emptyStateHeading('هنوز هیچ ورودیِ دانشی وجود ندارد')
            ->emptyStateDescription('واقعیت‌هایی درباره‌ی برند/کسب‌وکار (بیوگرافی، خدمات، سیاست‌ها، مکان‌ها، ...) اضافه کنید که دستیارِ هوشِ مصنوعی باید پیش از نوشتنِ محتوا بداند.')
            ->defaultSort('updated_at', 'desc');
    }
}

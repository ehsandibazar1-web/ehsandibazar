<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Model\Article;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('')
                    ->disk('public')
                    ->height(40)
                    ->square(),

                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),

                TextColumn::make('lang')
                    ->label('زبان')
                    ->badge()
                    ->color('gray'),

                IconColumn::make('status')
                    ->label('منتشر شده')
                    ->boolean(),

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
                    ->options(['fa' => 'فارسی', 'en' => 'English', 'tr' => 'Türkçe']),
            ])
            ->recordActions([
                self::previewAction(),
                self::duplicateAction(),
                self::cloneTranslationAction(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    // نمایش روی سایت (برای مقاله‌های منتشرشده؛ پیش‌نویس روی storefront ۴۰۴ می‌دهد).
    private static function previewAction(): Action
    {
        return Action::make('preview')
            ->label('نمایش')
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->url(fn (Article $record): string => url('article/'.$record->slug))
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
}

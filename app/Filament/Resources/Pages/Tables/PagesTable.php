<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Model\Page;
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

class PagesTable
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

                // مقدارِ خامِ created_at (accessorِ شمسیِ legacy را دور می‌زنیم).
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
            ])
            ->recordActions([
                self::previewAction(),
                self::duplicateAction(),
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

    private static function previewAction(): Action
    {
        return Action::make('preview')
            ->label('نمایش')
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->url(fn (Page $record): string => url('page/'.$record->slug))
            ->openUrlInNewTab();
    }

    private static function duplicateAction(): Action
    {
        return Action::make('duplicate')
            ->label('تکثیر')
            ->icon('heroicon-o-document-duplicate')
            ->color('gray')
            ->requiresConfirmation()
            ->action(function (Page $record): void {
                $copy = $record->replicate(['viewCount']);
                $copy->title = $record->title.' (کپی)';
                $copy->slug = $record->slug.'-copy-'.Str::lower(Str::random(4));
                $copy->status = 0;
                $copy->published_at = null;
                $copy->translation_of = null;
                $copy->viewCount = 0;
                $copy->save();

                Notification::make()->success()->title('صفحه به‌عنوانِ پیش‌نویسِ جدید تکثیر شد')->send();
            });
    }
}

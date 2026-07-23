<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('question')
                    ->label('متنِ پرسش')
                    ->limit(60)
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('state')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match ((int) $state) {
                        1 => 'تایید شده',
                        default => 'در انتظار تایید',
                    })
                    ->color(fn ($state): string => match ((int) $state) {
                        1 => 'success',
                        default => 'warning',
                    }),

                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز پرسشی ثبت نشده')
            ->emptyStateDescription('پرسش‌های ثبت‌شده‌ی کاربران اینجا نمایش داده می‌شوند.');
    }
}

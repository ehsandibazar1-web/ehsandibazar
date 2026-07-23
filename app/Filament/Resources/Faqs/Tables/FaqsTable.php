<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('پرسش')
                    ->searchable()
                    ->sortable()
                    ->limit(60),

                TextColumn::make('description')
                    ->label('پاسخ')
                    ->limit(60)
                    ->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز پرسشی ثبت نشده')
            ->emptyStateDescription('سوالاتِ متداول برای راهنماییِ کاربران روی سایت استفاده می‌شوند.');
    }
}

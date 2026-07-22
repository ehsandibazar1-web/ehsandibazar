<?php

namespace App\Filament\Resources\Newsletter\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('mobile')
                    ->label('موبایل')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('تاریخِ عضویت')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز مشترکی نیست')
            ->emptyStateDescription('ایمیل‌هایی که از فرمِ خبرنامه‌ی سایت ثبت می‌شوند اینجا نمایش داده می‌شوند.');
    }
}

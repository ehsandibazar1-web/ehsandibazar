<?php

namespace App\Filament\Resources\Contacts\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام کاربر')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('body')
                    ->label('متن پیام')
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state) => (int) $state === 1 ? 'فعال' : 'غیر فعال')
                    ->color(fn ($state) => (int) $state === 1 ? 'success' : 'danger'),

                TextColumn::make('lang')
                    ->label('زبان')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز پیامی ثبت نشده')
            ->emptyStateDescription('پیام‌هایی که بازدیدکنندگان از فرمِ تماس با ما ارسال می‌کنند اینجا نمایش داده می‌شوند.');
    }
}

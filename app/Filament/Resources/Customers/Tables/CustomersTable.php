<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('شناسه')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('family')
                    ->label('نام خانوادگی')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('mobile')
                    ->label('موبایل')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('wallet')
                    ->label('کیفِ پول')
                    ->numeric()
                    ->toggleable(),

                IconColumn::make('active')
                    ->label('فعال')
                    ->boolean(),

                IconColumn::make('block')
                    ->label('مسدود')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('تاریخِ ثبت')
                    ->dateTime('Y/m/d')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('active')
                    ->label('فعال'),

                TernaryFilter::make('block')
                    ->label('مسدود'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز کاربری ثبت نشده')
            ->emptyStateDescription('مشتریان و کاربرانِ سایت اینجا نمایش داده می‌شوند.');
    }
}

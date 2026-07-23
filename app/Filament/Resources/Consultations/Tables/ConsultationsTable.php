<?php

namespace App\Filament\Resources\Consultations\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConsultationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام و نام خانوادگی')
                    ->searchable(),

                TextColumn::make('mobile')
                    ->label('شماره همراه')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('purpose_exercise')
                    ->label('هدف از تمرینات')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state) => (int) $state === 1 ? 'فعال' : 'غیر فعال')
                    ->color(fn ($state) => (int) $state === 1 ? 'success' : 'danger'),

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
            ->emptyStateHeading('هنوز درخواستی ثبت نشده')
            ->emptyStateDescription('درخواست‌هایی که بازدیدکنندگان از فرمِ مشاوره ارسال می‌کنند اینجا نمایش داده می‌شوند.');
    }
}

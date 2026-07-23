<?php

namespace App\Filament\Resources\Exams\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mobile')
                    ->label('موبایل')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('description')
                    ->label('توضیحات')
                    ->limit(60)
                    ->placeholder('—')
                    ->toggleable(),

                // توجه: مدلِ Exam دارای getCreatedAtAttribute (رشته‌ی جلالی) است؛
                // اینجا عمداً از ->dateTime()/->date() استفاده نمی‌شود تا Carbon سعی به پارس نکند.
                TextColumn::make('created_at')
                    ->label('تاریخِ ثبت')
                    ->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز درخواستِ آزمونی ثبت نشده')
            ->emptyStateDescription('درخواست‌های آزمونِ ثبت‌شده از سایت اینجا نمایش داده می‌شوند.');
    }
}

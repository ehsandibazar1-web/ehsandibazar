<?php

namespace App\Filament\Resources\Menus\Tables;

use App\Model\Menu;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('src')
                    ->label('نشانی')
                    ->limit(40)
                    ->color('gray'),

                TextColumn::make('parent')
                    ->label('والد')
                    ->state(fn (Menu $record): string => $record->parent_id
                        ? (Menu::query()->whereKey($record->parent_id)->value('title') ?? '—')
                        : 'منوی اصلی'),

                TextColumn::make('lang')
                    ->label('زبان')
                    ->badge(),

                TextColumn::make('sorting')
                    ->label('ترتیب')
                    ->sortable(),

                IconColumn::make('status')
                    ->label('فعال')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ])
            ->defaultSort('sorting')
            ->emptyStateHeading('هنوز آیتمِ منویی نیست')
            ->emptyStateDescription('آیتم‌های نویگیشنِ هدرِ سایت را اینجا اضافه/مرتب کنید.');
    }
}

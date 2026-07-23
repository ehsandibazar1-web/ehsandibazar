<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Resources\Products\Schemas\ProductForm;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('variations'))
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('code')
                    ->label('کد')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('brand.title')
                    ->label('برند')
                    ->badge()
                    ->color('info')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('نوع')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ProductForm::TYPES[$state] ?? $state),

                TextColumn::make('variations_count')
                    ->label('تنوع‌ها')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('soldCount')
                    ->label('فروش')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('status')
                    ->label('فعال')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('تاریخِ ثبت')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->label('وضعیت')
                    ->placeholder('همه')
                    ->trueLabel('فعال')
                    ->falseLabel('غیرفعال'),

                SelectFilter::make('type')
                    ->label('نوعِ محصول')
                    ->options(ProductForm::TYPES),

                SelectFilter::make('brand')
                    ->label('برند')
                    ->relationship('brand', 'title')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز محصولی ثبت نشده')
            ->emptyStateDescription('محصولاتِ فروشگاه اینجا مدیریت می‌شوند؛ قیمت و موجودی در تبِ «تنوع‌ها» هر محصول است.');
    }
}

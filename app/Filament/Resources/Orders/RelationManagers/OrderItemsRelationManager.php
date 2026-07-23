<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * اقلامِ سفارش — فقط-خواندنی. آیتم‌ها هنگامِ تسویه ساخته می‌شوند، پس اینجا افزودن/ویرایش/حذف نداریم.
 * رابطه‌ی 'orderItem' روی مدلِ App\Model\Order (hasMany OrderItem).
 */
class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItem';

    protected static ?string $title = 'اقلامِ سفارش';

    protected static ?string $recordTitleAttribute = 'id';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.title')
                    ->label('محصول')
                    ->placeholder('—'),

                TextColumn::make('itemCount')
                    ->label('تعداد'),

                TextColumn::make('amount')
                    ->label('مبلغ')
                    ->numeric(),

                TextColumn::make('amount_discount')
                    ->label('تخفیف')
                    ->numeric(),
            ])
            ->recordActions([])
            ->emptyStateHeading('این سفارش قلمی ندارد');
    }
}

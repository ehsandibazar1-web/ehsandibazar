<?php

namespace App\Filament\Resources\Payments;

use App\Filament\Resources\Payments\Pages\ListPayments;
use App\Filament\Resources\Payments\Tables\PaymentsTable;
use App\Model\Payment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * پرداخت‌ها — نمایشِ فقط-خواندنیِ رکوردهای مالیِ مدلِ زنده‌ی App\Model\Payment.
 * این رکوردها توسطِ فرآیندِ تسویه/درگاه ساخته می‌شوند؛ ادمین اجازه‌ی ساخت/ویرایش/حذف ندارد.
 * storefront و migration و خودِ مدل دست‌نخورده می‌مانند.
 */
class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'فروش';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'پرداخت‌ها';

    protected static ?string $recordTitleAttribute = 'id';

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
        ];
    }
}

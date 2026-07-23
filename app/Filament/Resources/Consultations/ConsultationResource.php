<?php

namespace App\Filament\Resources\Consultations;

use App\Filament\Resources\Consultations\Pages\EditConsultation;
use App\Filament\Resources\Consultations\Pages\ListConsultations;
use App\Filament\Resources\Consultations\Schemas\ConsultationForm;
use App\Filament\Resources\Consultations\Tables\ConsultationsTable;
use App\Model\Consultation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ درخواست‌های مشاوره — نمایش و مدیریتِ وضعیتِ فرم‌هایی که بازدیدکنندگان برای درخواستِ مشاوره‌ی
 * ورزشی پُر می‌کنند. روی مدلِ زنده‌ی App\Model\Consultation کار می‌کند. فقط ابزارِ ادمین است؛
 * storefront و مدل و مهاجرت‌ها دست‌نخورده می‌مانند. چون محتوا را بازدیدکننده ثبت می‌کند، ایجادِ رکورد
 * غیرفعال است و فقط وضعیت قابل ویرایش است.
 */
class ConsultationResource extends Resource
{
    protected static ?string $model = Consultation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static string|UnitEnum|null $navigationGroup = 'پشتیبانی';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'درخواست‌های مشاوره';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return ConsultationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsultationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConsultations::route('/'),
            'edit' => EditConsultation::route('/{record}/edit'),
        ];
    }
}

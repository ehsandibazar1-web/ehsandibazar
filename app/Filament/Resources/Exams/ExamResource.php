<?php

namespace App\Filament\Resources\Exams;

use App\Filament\Resources\Exams\Pages\CreateExam;
use App\Filament\Resources\Exams\Pages\EditExam;
use App\Filament\Resources\Exams\Pages\ListExams;
use App\Filament\Resources\Exams\Schemas\ExamForm;
use App\Filament\Resources\Exams\Tables\ExamsTable;
use App\Model\Exam;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ درخواست‌های آزمون روی مدلِ زنده‌ی App\Model\Exam (name/mobile/description).
 * توجه: مدلِ Exam دارای getCreatedAtAttribute است که رشته‌ی جلالی برمی‌گرداند؛ به همین دلیل
 * ستونِ created_at در جدول به‌صورتِ TextColumnِ ساده (بدونِ dateTime) رندر می‌شود.
 * storefront/migration/model دست‌نخورده؛ فقط ابزارِ مدیریتِ ادمین.
 */
class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'آموزش';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'آزمون‌ها';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ExamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExamsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExams::route('/'),
            'create' => CreateExam::route('/create'),
            'edit' => EditExam::route('/{record}/edit'),
        ];
    }
}

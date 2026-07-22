<?php

namespace App\Filament\Resources\ContentPlans;

use App\Filament\Resources\ContentPlans\Pages\CreateContentPlan;
use App\Filament\Resources\ContentPlans\Pages\EditContentPlan;
use App\Filament\Resources\ContentPlans\Pages\ListContentPlans;
use App\Filament\Resources\ContentPlans\Schemas\ContentPlanForm;
use App\Filament\Resources\ContentPlans\Tables\ContentPlanTable;
use App\Models\ContentPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * برنامه‌ریزِ محتوا (CRUD) — پورت از سایتِ انگلیسی. در انگلیسی این ریسورس از منو پنهان است و صفحه‌ی
 * کانبانِ ContentPlanner مرکزِ اصلی است؛ اینجا فعلاً «قابل‌مشاهده» است تا پیش از ساختِ صفحه‌ی کانبان
 * هم قابل‌استفاده باشد. کارت می‌تواند یک ایده‌ی محض (بدونِ Article/Page) باشد تا رسیدن به AI Draft.
 */
class ContentPlanResource extends Resource
{
    protected static ?string $model = ContentPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Content Planner';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Content Plans';

    protected static ?string $slug = 'content-plans';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return ContentPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentPlanTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContentPlans::route('/'),
            'create' => CreateContentPlan::route('/create'),
            'edit' => EditContentPlan::route('/{record}/edit'),
        ];
    }
}

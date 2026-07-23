<?php

namespace App\Filament\Resources\Articles;

use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Filament\Resources\Articles\Schemas\ArticleForm;
use App\Filament\Resources\Articles\Tables\ArticlesTable;
use App\Model\Article;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationLabel = 'مقاله‌ها';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return ArticleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArticlesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit' => EditArticle::route('/{record}/edit'),
        ];
    }

    /**
     * ترجمه‌ی فیلدِ مجازیِ فرم (publish_state) به ستون‌های واقعی هنگامِ ذخیره. به موتورِ
     * زمان‌بندیِ خودکار (articles:publish-due) وصل است: زمان‌بندی‌شده = status=0 + is_scheduled=1.
     */
    public static function applyPublishState(array $data): array
    {
        $state = $data['publish_state'] ?? null;
        unset($data['publish_state']);

        if ($state === 'published') {
            $data['status'] = 1;
            $data['is_scheduled'] = false;
            $data['published_at'] = $data['published_at'] ?? now();
        } elseif ($state === 'scheduled') {
            $data['status'] = 0;
            $data['is_scheduled'] = true;
            // published_at از خودِ فرم (تاریخِ آینده) می‌آید.
        } elseif ($state === 'draft') {
            $data['status'] = 0;
            $data['is_scheduled'] = false;
        }

        return $data;
    }

    /** مشتق‌کردنِ publish_state از رکورد هنگامِ پرکردنِ فرمِ ویرایش. */
    public static function derivePublishState(array $data): array
    {
        $data['publish_state'] = ((int) ($data['status'] ?? 0) === 1)
            ? 'published'
            : (! empty($data['is_scheduled']) ? 'scheduled' : 'draft');

        return $data;
    }
}

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

    /**
     * همگام‌سازیِ تصویرِ شاخصِ فرم (ستونِ image_path) با رابطه‌ی image() که storefront می‌خواند.
     *
     * چرا لازم است: storefront تصویرِ شاخص را همیشه اول از رابطه‌ی image() می‌خواند
     * (`$article->image[0]->url` در article/articles blade و JSON-LD و OG) و فقط وقتی آن خالی
     * باشد به image_path برمی‌گردد. پس تغییرِ تصویر از پنلِ جدید (که فقط image_path را می‌نوشت)
     * روی سایت دیده نمی‌شد. این متد رابطه را با انتخابِ جدید هماهنگ می‌کند تا تغییر واقعاً اعمال شود.
     *
     * ایمنی (خطِ قرمزِ سئو):
     *  - فقط از صفحاتِ ادمین و فقط وقتی image_path در همان ذخیره «تغییر کرده» صدا زده می‌شود
     *    (wasChanged/filled) — مقاله‌های موجودی که ادمین دستشان نمی‌زند کاملاً دست‌نخورده می‌مانند.
     *  - هیچ ویو/کنترلرِ storefront تغییر نمی‌کند؛ فقط داده‌ی رابطه با کنشِ عمدیِ ادمین هماهنگ می‌شود.
     *  - مقدارِ ذخیره‌شده در رابطه دقیقاً همان قراردادِ fallbackِ خودِ storefront است
     *    (`/storage/` + image_path)، پس رندر بایت‌به‌بایت با مسیرِ image_path یکی است.
     *  - خالی‌کردنِ فیلد، رابطه را حذف نمی‌کند (حذفِ هیروِ ایندکس‌شده خطرناک است) — برای جایگزینی،
     *    ادمین یک تصویرِ تازه انتخاب می‌کند.
     */
    public static function syncFeaturedImageRelation(Article $record): void
    {
        $imagePath = $record->image_path;

        if (blank($imagePath)) {
            return; // خالی — رابطه را دست نمی‌زنیم (سازگاریِ عقب‌رو)
        }

        // نشانیِ ریشه‌ایِ عمومیِ همان فایل (بدونِ هاست) — همان قراردادی که storefront برای fallbackِ
        // image_path دارد: asset('storage/'.$image_path). Image::getUrlAttribute یک‌بار APP_URL را
        // جلو می‌گذارد، پس اینجا فقط مسیرِ ریشه‌ای ذخیره می‌شود تا دوبار پیشوند نخورد.
        $rawUrl = '/storage/'.ltrim((string) $imagePath, '/');

        $image = $record->image()->first();

        if ($image) {
            if ($image->getRawOriginal('url') !== $rawUrl) {
                $image->url = $rawUrl; // setUrlAttribute فقط APP_URL را حذف می‌کند → خام همین می‌ماند
                $image->save();
            }

            return;
        }

        $record->image()->create([
            'url' => $rawUrl,
            'user_id' => auth()->id() ?? $record->user_id,
        ]);
    }
}

<?php

namespace App\Filament\Pages;

use App\Model\Article;
use App\Model\Page as PageModel;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

/**
 * پوسته‌ی نازک اطراف App\Livewire\AiAssistantPanel — تمام منطق واقعی (generate/apply/restore/...)
 * در آن کامپوننت است تا هم اینجا (دسترسی مستقیم/لینک‌دهی تمام‌صفحه) و هم سایدبارِ تعبیه‌شده در
 * EditArticle/EditPage از یک کد واحد استفاده کنند. این صفحه از ناوبری مخفی است اما آدرسش کار می‌کند.
 */
class AiContentAssistant extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'AI Studio';

    // صفحه‌ی زمینه‌ای است (با ?article= یا ?page= باز می‌شود و بدونِ رکورد 404 می‌دهد)،
    // پس نباید در منو ظاهر شود — از ویرایشِ مقاله/صفحه باز می‌شود.
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'AI Content Assistant';

    protected string $view = 'filament.pages.ai-content-assistant';

    public ?Model $record = null;

    public string $recordType = 'Article';

    public function mount(): void
    {
        $articleId = request()->integer('article');
        $pageId = request()->integer('page');

        if ($articleId) {
            $this->record = Article::find($articleId);
            $this->recordType = 'Article';
        } elseif ($pageId) {
            $this->record = PageModel::find($pageId);
            $this->recordType = 'Page';
        }

        abort_if(! $this->record, 404);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}

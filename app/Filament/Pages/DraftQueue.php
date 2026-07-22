<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use App\Model\Article;
use App\Models\ImportLog;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use UnitEnum;

/**
 * صفِ پیش‌نویس‌ها — مقاله‌هایی که از AI Import آمده‌اند و هنوز منتشر/بازگردانی نشده‌اند، تا ادمین
 * یک‌جا بررسی و منتشرشان کند. نسخه‌ی فارسیِ صفحه‌ی Draft Queue انگلیسی، نگاشته به اسکیمای زنده
 * (statusِ بولین، ستونِ lang، accessorِ شمسیِ created_at).
 */
class DraftQueue extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxStack;

    protected static string|UnitEnum|null $navigationGroup = 'AI Studio';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Draft Queue';

    protected static ?string $title = 'صفِ پیش‌نویس‌ها';

    protected string $view = 'filament.pages.draft-queue';

    public function table(Table $table): Table
    {
        return $table
            ->query(Article::query()
                ->where('status', 0) // پیش‌نویس (بولین)
                ->whereIn('id', ImportLog::query()
                    ->where('status', 'imported')
                    ->whereNull('rolled_back_at')
                    ->whereNotNull('article_id')
                    ->select('article_id')))
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->limit(45),

                TextColumn::make('lang')
                    ->label('زبان')
                    ->badge(),

                // accessorِ getCreatedAtAttribute رشته‌ی شمسی برمی‌گرداند؛ مقدارِ خام را می‌خوانیم.
                TextColumn::make('created')
                    ->label('زمانِ ایمپورت')
                    ->state(fn (Article $record): string => ($raw = $record->getRawOriginal('created_at'))
                        ? Carbon::parse($raw)->format('Y-m-d H:i')
                        : '—'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('ویرایش')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (Article $record): string => ArticleResource::getUrl('edit', ['record' => $record])),

                Action::make('view')
                    ->label('نمایش روی سایت')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Article $record): string => url('article/'.$record->slug))
                    ->openUrlInNewTab(),

                Action::make('publishNow')
                    ->label('انتشار')
                    ->icon('heroicon-o-rocket-launch')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription(fn (Article $record): string => '«'.$record->title.'» بلافاصله روی سایت منتشر می‌شود.')
                    ->action(function (Article $record): void {
                        // از طریق Eloquent تا اگر activitylog روی مقاله فعال شد، ثبت شود
                        $record->update(['status' => 1, 'published_at' => now()]);

                        Notification::make()->success()->title('منتشر شد: '.$record->title)->send();
                    }),
            ])
            ->emptyStateHeading('صفِ پیش‌نویس خالی است')
            ->emptyStateDescription('مقاله‌هایی که از AI Import به‌صورتِ پیش‌نویس ساخته می‌شوند، تا بررسی و انتشار اینجا می‌مانند.')
            ->defaultSort('id', 'desc');
    }
}

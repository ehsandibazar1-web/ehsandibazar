<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use UnitEnum;

/**
 * نمایشگرِ لاگِ فعالیت — روی spatie/activitylog (که از گروه ۲ نصب است). فعلاً موضوع‌های ثبت‌شده
 * BrandMemoryValue هستند؛ با آمدنِ Article/Page در موج ۴، همان جدول به‌صورت خودکار آن‌ها را هم
 * نشان می‌دهد. برای همین ستونِ «موضوع» generic است (نوع + شناسه)، نه مخصوصِ مقاله.
 */
class ActivityLogPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'AI Studio';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Activity Log';

    protected static ?string $title = 'Activity Log';

    protected string $view = 'filament.pages.activity-log-page';

    public function table(Table $table): Table
    {
        return $table
            ->query(Activity::query())
            ->columns([
                TextColumn::make('causer.name')
                    ->label('User')
                    ->default('System')
                    ->searchable(),

                TextColumn::make('log_name')
                    ->label('Type')
                    ->badge()
                    ->color('gray')
                    ->default('—'),

                // موضوع generic: نوعِ کلاس (بدون namespace) + شناسه — با هر مدلِ ثبت‌شده‌ای کار می‌کند
                TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn (?string $state, Activity $record): string => $state
                        ? class_basename($state).' #'.$record->subject_id
                        : '— (deleted)'),

                TextColumn::make('description')
                    ->label('Action')
                    ->badge()
                    ->color(fn (?string $state): string => match (true) {
                        str_contains($state ?? '', 'published') => 'success',
                        str_contains($state ?? '', 'created') => 'info',
                        str_contains($state ?? '', 'restored') => 'warning',
                        str_contains($state ?? '', 'deleted') => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}

<?php

namespace App\Filament\Resources\ContentPlans\Tables;

use App\Model\Tag;
use App\Models\ContentPlan;
use App\Models\WorkflowStage;
use App\Services\AiAssistant\ContentReviewService;
use App\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContentPlanTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('locale')
                    ->label('زبان')
                    ->badge(),

                TextColumn::make('category')
                    ->label('دسته')
                    ->default('—')
                    ->toggleable(),

                TextColumn::make('tags.title')
                    ->label('تگ‌ها')
                    ->badge()
                    ->separator(',')
                    ->toggleable(),

                TextColumn::make('author.name')
                    ->label('نویسنده')
                    ->default('—'),

                TextColumn::make('assignee.name')
                    ->label('واگذار به')
                    ->default('—'),

                TextColumn::make('workflowStage.label')
                    ->label('مرحله')
                    ->badge()
                    ->color(fn (ContentPlan $record): string => $record->workflowStage?->color ?? 'gray'),

                TextColumn::make('priority')
                    ->label('اولویت')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'critical' => 'danger',
                        'high' => 'warning',
                        'low' => 'gray',
                        default => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'کم', 'high' => 'زیاد', 'critical' => 'بحرانی', default => 'متوسط',
                    }),

                TextColumn::make('seo_score')
                    ->label('امتیازِ سئو')
                    ->state(fn (ContentPlan $record): ?int => self::scoreCardFor($record)['categories']['seo']['score'] ?? null)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('ai_score')
                    ->label('امتیازِ AI')
                    ->state(fn (ContentPlan $record): ?int => self::scoreCardFor($record)['overall'] ?? null)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('publish_date')
                    ->label('تاریخِ انتشار')
                    ->state(fn (ContentPlan $record) => $record->effectivePublishDate())
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—'),

                TextColumn::make('updated_at')
                    ->label('آخرین به‌روزرسانی')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('workflow_stage_id')
                    ->label('مرحله')
                    ->options(fn () => WorkflowStage::orderBy('sort_order')->pluck('label', 'id')),

                SelectFilter::make('locale')
                    ->label('زبان')
                    ->options(['fa' => 'فارسی', 'en' => 'English']),

                SelectFilter::make('author_id')
                    ->label('نویسنده')
                    ->options(fn () => User::query()->pluck('name', 'id')),

                SelectFilter::make('category')
                    ->label('دسته')
                    ->options(fn () => ContentPlan::query()->whereNotNull('category')->distinct()->pluck('category', 'category')->all()),

                SelectFilter::make('tags')
                    ->label('تگ')
                    ->relationship('tags', 'title')
                    ->options(fn () => Tag::query()->pluck('title', 'id')),

                SelectFilter::make('priority')
                    ->label('اولویت')
                    ->options([
                        'low' => 'کم',
                        'medium' => 'متوسط',
                        'high' => 'زیاد',
                        'critical' => 'بحرانی',
                    ]),

                SelectFilter::make('contentable_type')
                    ->label('وضعیتِ انتشار')
                    ->options([
                        'none' => 'هنوز محتوایی ندارد',
                        'article' => 'مقاله',
                        'page' => 'صفحه',
                    ])
                    ->query(function ($query, array $data) {
                        $value = $data['value'] ?? null;

                        return match ($value) {
                            null, '' => $query,
                            'none' => $query->whereNull('contentable_type'),
                            default => $query->where('contentable_type', $value),
                        };
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('هنوز برنامه‌ی محتوایی نیست')
            ->emptyStateDescription('یک ایده اضافه کنید تا خط‌تولید شروع شود — تا رسیدن به AI Draft نیازی به Article/Page واقعی ندارد.');
    }

    private static function scoreCardFor(ContentPlan $record): array
    {
        if (! $record->contentable) {
            return [];
        }

        return app(ContentReviewService::class)->scoreCard($record->contentable);
    }
}

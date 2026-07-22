<?php

namespace App\Filament\Resources\ContentPlans\Schemas;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Pages\PageResource;
use App\Models\ContentPlan;
use App\Models\ContentTask;
use App\Models\WorkflowStage;
use App\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class ContentPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('کلیات')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان')
                            ->required()
                            ->columnSpanFull(),

                        Select::make('locale')
                            ->label('زبان')
                            ->options(['fa' => 'فارسی', 'en' => 'English'])
                            ->default('fa')
                            ->required(),

                        Select::make('content_type')
                            ->label('نوعِ محتوا')
                            ->options(['Article' => 'مقاله (بلاگ)', 'Page' => 'صفحه (مستقل)'])
                            ->nullable()
                            ->helperText('وقتی به مرحله‌ی AI Draft برسد چه می‌شود — خالی بگذارید تا بعداً تصمیم بگیرید.'),

                        TextInput::make('category')
                            ->label('دسته')
                            ->nullable(),

                        Select::make('priority')
                            ->label('اولویت')
                            ->options([
                                ContentPlan::PRIORITY_LOW => 'کم',
                                ContentPlan::PRIORITY_MEDIUM => 'متوسط',
                                ContentPlan::PRIORITY_HIGH => 'زیاد',
                                ContentPlan::PRIORITY_CRITICAL => 'بحرانی',
                            ])
                            ->default(ContentPlan::PRIORITY_MEDIUM)
                            ->required(),

                        Select::make('workflow_stage_id')
                            ->label('مرحله‌ی خط‌تولید')
                            ->options(fn () => WorkflowStage::orderBy('sort_order')->pluck('label', 'id'))
                            ->default(fn () => WorkflowStage::default()?->id)
                            ->required()
                            ->helperText('رسیدن به مرحله‌ی AI Draft یک پیش‌نویسِ Article/Page خودکار می‌سازد.'),

                        Select::make('tags')
                            ->relationship('tags', 'title')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('تگ‌ها'),

                        Placeholder::make('contentable_link')
                            ->label('محتوای متصل')
                            ->columnSpanFull()
                            ->visible(fn (?ContentPlan $record): bool => $record !== null)
                            ->content(function (?ContentPlan $record) {
                                if (! $record?->contentable) {
                                    return 'هنوز ساخته نشده — رسیدن به مرحله‌ی AI Draft یک پیش‌نویسِ Article/Page از این ایده می‌سازد.';
                                }

                                $url = $record->contentable_type === 'page'
                                    ? PageResource::getUrl('edit', ['record' => $record->contentable_id])
                                    : ArticleResource::getUrl('edit', ['record' => $record->contentable_id]);

                                return new HtmlString('<a href="'.e($url).'" class="underline">'.e($record->contentable->title).' →</a>');
                            }),
                    ]),

                Section::make('مالکیت و زمان‌بندی')
                    ->columns(2)
                    ->schema([
                        Select::make('author_id')
                            ->label('نویسنده')
                            ->options(fn () => User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        Select::make('assigned_to')
                            ->label('واگذارشده به')
                            ->options(fn () => User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        DateTimePicker::make('planned_publish_at')
                            ->label('تاریخِ انتشارِ برنامه‌ریزی‌شده')
                            ->nullable()
                            ->helperText('فقط تا وقتی Article/Page واقعی متصل نشده استفاده می‌شود؛ سپس تقویم از تاریخِ انتشارِ خودِ آن رکورد پیروی می‌کند.'),

                        DateTimePicker::make('due_at')
                            ->label('مهلتِ پیش‌نویس')
                            ->nullable(),
                    ]),

                Section::make('کارها')
                    ->schema([
                        Repeater::make('tasks')
                            ->relationship()
                            ->orderColumn('sort_order')
                            ->label('کارها')
                            ->schema([
                                TextInput::make('title')
                                    ->label('کار')
                                    ->required()
                                    ->columnSpanFull(),

                                Select::make('status')
                                    ->label('وضعیت')
                                    ->options([
                                        ContentTask::STATUS_PENDING => 'در انتظار',
                                        ContentTask::STATUS_IN_PROGRESS => 'در حالِ انجام',
                                        ContentTask::STATUS_DONE => 'انجام‌شده',
                                    ])
                                    ->default(ContentTask::STATUS_PENDING)
                                    ->required(),

                                DateTimePicker::make('due_at')
                                    ->label('موعد')
                                    ->nullable(),

                                Select::make('assigned_to')
                                    ->label('واگذار به')
                                    ->options(fn () => User::query()->pluck('name', 'id'))
                                    ->searchable()
                                    ->nullable(),

                                Textarea::make('notes')
                                    ->label('یادداشت')
                                    ->rows(2)
                                    ->nullable()
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->addActionLabel('افزودنِ کار')
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->defaultItems(0)
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Section::make('چک‌لیستِ مرحله')
                    ->visible(fn (?ContentPlan $record): bool => $record !== null && filled($record->workflowStage?->checklist_items))
                    ->schema(function (?ContentPlan $record) {
                        $stage = $record?->workflowStage;

                        if (! $stage || blank($stage->checklist_items)) {
                            return [];
                        }

                        return collect($stage->checklist_items)
                            ->map(fn (array $item) => Checkbox::make("checklist_state.{$stage->slug}.{$item['key']}")->label($item['label']))
                            ->all();
                    })
                    ->columns(2),

                Grid::make(1)->schema([
                    Textarea::make('notes')
                        ->label('یادداشت‌ها')
                        ->rows(3)
                        ->nullable(),
                ]),
            ]);
    }
}

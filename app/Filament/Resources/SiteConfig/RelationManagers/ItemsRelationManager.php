<?php

namespace App\Filament\Resources\SiteConfig\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * آیتم‌های یک بخشِ تنظیماتِ سایت (مدلِ App\Model\Systeminfmanage). ستون‌های code..code5 «چندمنظوره‌اند»
 * و معنایشان بسته به بخش فرق می‌کند (مثلاً در اسلایدر: code تصویر و code5 لینک است؛ در «درباره»
 * code متن است). چون همان داده‌ای است که سایت از آن می‌خوانَد، ویرایش اینجا مستقیماً روی سایت اثر
 * دارد. مقادیرِ فعلیِ هر آیتم نشان می‌دهد هر خانه چه‌کاره است.
 */
class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'systeminfmanage';

    protected static ?string $title = 'آیتم‌های این بخش';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('عنوان / متنِ اصلی (name)')
                    ->maxLength(255)
                    ->helperText('در بیشترِ بخش‌ها عنوان یا متنِ کوتاه است.'),

                TextInput::make('code')
                    ->label('مقدارِ ۱ (code)')
                    ->maxLength(255)
                    ->helperText('اغلب مسیرِ تصویر یا یک مقدارِ متنی است — به مقدارِ فعلی نگاه کنید.'),

                TextInput::make('code2')
                    ->label('مقدارِ ۲ (code2)')
                    ->maxLength(255),

                TextInput::make('code3')
                    ->label('مقدارِ ۳ (code3)')
                    ->maxLength(255),

                Textarea::make('code4')
                    ->label('مقدارِ ۴ (code4 — متنِ بلند)')
                    ->rows(3),

                TextInput::make('code5')
                    ->label('لینک / آدرس (code5)')
                    ->helperText('اگر لینک است، نشانیِ کامل را بگذارید؛ خودکار نسبت به دامنه ذخیره می‌شود.'),

                Toggle::make('status')
                    ->label('فعال (نمایش روی سایت)')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('شناسه')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('عنوان')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('code')
                    ->label('مقدارِ ۱')
                    ->limit(30)
                    ->toggleable(),

                TextColumn::make('code5')
                    ->label('لینک')
                    ->limit(30)
                    ->toggleable(),

                IconColumn::make('status')
                    ->label('فعال')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('افزودنِ آیتم'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('این آیتم از سایت حذف می‌شود. مطمئن هستید؟'),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('این بخش هنوز آیتمی ندارد');
    }
}

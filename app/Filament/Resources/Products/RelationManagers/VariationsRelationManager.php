<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Model\AttributeTypeValue;
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
 * تنوع‌های محصول — قیمت و موجودی و تخفیفِ هر تنوع اینجاست (مدلِ App\Model\Variation).
 * قیمت‌ها به تومان ذخیره می‌شوند (همان ستونِ price که فروشگاه از قبل استفاده می‌کند).
 */
class VariationsRelationManager extends RelationManager
{
    protected static string $relationship = 'variations';

    protected static ?string $title = 'تنوع‌ها (قیمت و موجودی)';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('attribute_type_value_id')
                    ->label('مقدارِ ویژگی (مثلاً رنگ/سایز)')
                    ->helperText('اختیاری — برای محصولاتِ ساده خالی بماند.'),

                TextInput::make('price')
                    ->label('قیمت (تومان)')
                    ->numeric()
                    ->required(),

                TextInput::make('discountPrice')
                    ->label('قیمت با تخفیف (تومان)')
                    ->numeric(),

                Toggle::make('discountActive')
                    ->label('تخفیف فعال باشد'),

                TextInput::make('count')
                    ->label('موجودی (تعداد)')
                    ->numeric()
                    ->default(0),

                Toggle::make('status')
                    ->label('فعال')
                    ->default(true),

                Textarea::make('description')
                    ->label('توضیح')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('attribute_type_value_id')
                    ->label('ویژگی')
                    ->formatStateUsing(fn ($state) => $state
                        ? optional(AttributeTypeValue::find($state))->value ?? $state
                        : '—'),

                TextColumn::make('price')
                    ->label('قیمت (تومان)')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('discountPrice')
                    ->label('با تخفیف')
                    ->numeric()
                    ->placeholder('—'),

                IconColumn::make('discountActive')
                    ->label('تخفیف')
                    ->boolean(),

                TextColumn::make('count')
                    ->label('موجودی')
                    ->numeric()
                    ->sortable(),

                IconColumn::make('status')
                    ->label('فعال')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data) {
                        $data['user_id'] = auth()->id();

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->emptyStateHeading('این محصول هنوز تنوع (قیمت) ندارد')
            ->emptyStateDescription('برای اینکه محصول قابلِ فروش شود حداقل یک تنوع با قیمت اضافه کنید.');
    }
}

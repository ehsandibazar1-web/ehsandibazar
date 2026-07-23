<?php

namespace App\Filament\Resources\Attributes\Schemas;

use App\Model\AttributeGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام (کلید)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('label')
                    ->label('برچسبِ نمایشی')
                    ->maxLength(255),

                Select::make('attribute_group_id')
                    ->label('گروهِ ویژگی')
                    ->options(fn () => AttributeGroup::query()
                        ->get(['id', 'name', 'label'])
                        ->mapWithKeys(fn ($group) => [
                            $group->id => $group->name ?: $group->label,
                        ])
                        ->all())
                    ->searchable(),

                Toggle::make('is_filter')
                    ->label('استفاده به‌عنوان فیلتر'),

                Toggle::make('status')
                    ->label('فعال')
                    ->default(true),
            ]);
    }
}

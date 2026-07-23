<?php

namespace App\Filament\Resources\Comments\Schemas;

use App\Utility\CommentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('comment')
                    ->label('متنِ دیدگاه')
                    ->rows(5)
                    ->columnSpanFull()
                    // مُدِراتورها محتوای کاربر را می‌خوانند، نه بازنویسی.
                    ->disabled(),

                TextInput::make('user.name')
                    ->label('کاربر')
                    ->disabled(),

                TextInput::make('ip')
                    ->label('IP')
                    ->disabled(),

                // تنها فیلدِ قابلِ ویرایش: وضعیتِ مُدِراسیون.
                Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        CommentStatus::NOT_ACCEPTET => 'در انتظار تایید',
                        CommentStatus::ACCEPTET => 'تایید شده',
                        CommentStatus::FAILED => 'رد شده',
                    ])
                    ->native(false)
                    ->required(),
            ]);
    }
}

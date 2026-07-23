<?php

namespace App\Filament\Resources\Consultations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ConsultationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام و نام خانوادگی')
                    ->disabled(),

                TextInput::make('mobile')
                    ->label('شماره همراه')
                    ->disabled(),

                TextInput::make('birth_date')
                    ->label('تاریخ تولد')
                    ->disabled(),

                TextInput::make('height')
                    ->label('قد')
                    ->disabled(),

                TextInput::make('weight')
                    ->label('وزن')
                    ->disabled(),

                Textarea::make('address')
                    ->label('محل سکونت')
                    ->disabled(),

                TextInput::make('job')
                    ->label('شغل')
                    ->disabled(),

                TextInput::make('history_sports_activities')
                    ->label('سابقه فعالیت ورزشی')
                    ->disabled(),

                Textarea::make('prohibition_sports')
                    ->label('سابقه منع ورزشی')
                    ->disabled(),

                Textarea::make('physical_limitations')
                    ->label('محدودیت جسمی')
                    ->disabled(),

                Textarea::make('fear_injury')
                    ->label('ترس آسیب دیدگی')
                    ->disabled(),

                Textarea::make('self_defense_skills')
                    ->label('ای کاش مهارت دفاع شخصی داشتم')
                    ->disabled(),

                Textarea::make('purpose_exercise')
                    ->label('هدف از تمرینات')
                    ->columnSpanFull()
                    ->disabled(),

                Textarea::make('get_acquainted')
                    ->label('نحوه‌ی آشنایی')
                    ->disabled(),

                TextInput::make('social_networkId')
                    ->label('آیدی شبکه اجتماعی')
                    ->disabled(),

                Select::make('status')
                    ->label('وضعیت')
                    ->options([
                        0 => 'غیر فعال',
                        1 => 'فعال',
                    ])
                    ->default(0)
                    ->required(),
            ]);
    }
}

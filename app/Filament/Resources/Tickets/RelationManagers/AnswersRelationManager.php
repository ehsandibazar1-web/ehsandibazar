<?php

namespace App\Filament\Resources\Tickets\RelationManagers;

use App\Utility\TicketType;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * پاسخ‌های تیکت — رابطه‌ی 'answer' روی مدلِ App\Model\Ticket (hasMany TicketAnswer).
 * ادمین می‌تواند پاسخ ثبت کند؛ user_id هنگامِ ساخت به کاربرِ واردشده نسبت داده می‌شود.
 */
class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answer';

    protected static ?string $title = 'پاسخ‌ها';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('answer')
                    ->label('پاسخ')
                    ->rows(5)
                    ->required()
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('وضعیت')
                    ->options(TicketType::TicketAnswerStatus())
                    ->native(false)
                    ->default(TicketType::WAITTING),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('answer')
                    ->label('پاسخ')
                    ->limit(60)
                    ->wrap(),

                TextColumn::make('user.name')
                    ->label('پاسخ‌دهنده')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->dateTime('Y/m/d'),
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
            ->emptyStateHeading('هنوز پاسخی ثبت نشده');
    }
}

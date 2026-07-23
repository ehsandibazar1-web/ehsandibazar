<?php

namespace App\Filament\Resources\Tickets\Tables;

use App\Utility\TicketType;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        $statusColors = [
            TicketType::OPEN => 'success',
            TicketType::WAITTING => 'warning',
            TicketType::OBSERVE => 'gray',
            TicketType::ANEWERED => 'success',
            TicketType::CLOSE => 'danger',
        ];

        $priorityColors = [
            TicketType::Low => 'gray',
            TicketType::Medium => 'warning',
            TicketType::High => 'danger',
        ];

        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('subject')
                    ->label('موضوع')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state) => TicketType::TicketStatus()[$state] ?? $state)
                    ->color(fn ($state) => $statusColors[$state] ?? 'gray'),

                TextColumn::make('priority')
                    ->label('اولویت')
                    ->badge()
                    ->formatStateUsing(fn ($state) => TicketType::TicketPriority()[$state] ?? $state)
                    ->color(fn ($state) => $priorityColors[$state] ?? 'gray'),

                TextColumn::make('departemans.name')
                    ->label('دپارتمان')
                    ->badge()
                    ->color('info')
                    ->placeholder('—'),

                TextColumn::make('tracking_code')
                    ->label('کدِ پیگیری')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->dateTime('Y/m/d')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options(TicketType::TicketStatus()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز تیکتی ثبت نشده')
            ->emptyStateDescription('تیکت‌ها از سمتِ مشتریان در بخشِ پشتیبانی ثبت می‌شوند.');
    }
}

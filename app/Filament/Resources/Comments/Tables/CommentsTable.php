<?php

namespace App\Filament\Resources\Comments\Tables;

use App\Utility\CommentStatus;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('کاربر')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('comment')
                    ->label('دیدگاه')
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('commentable_type')
                    ->label('روی')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (?string $state): string => match (class_basename($state ?? '')) {
                        'Article' => 'مقاله',
                        'Product' => 'محصول',
                        'Post' => 'پست',
                        default => class_basename($state ?? '') ?: '—',
                    }),

                TextColumn::make('status')
                    ->label('وضعیت')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match ((int) $state) {
                        CommentStatus::ACCEPTET => 'تایید شده',
                        CommentStatus::NOT_ACCEPTET => 'در انتظار تایید',
                        CommentStatus::FAILED => 'رد شده',
                        default => (string) $state,
                    })
                    ->color(fn ($state): string => match ((int) $state) {
                        CommentStatus::ACCEPTET => 'success',
                        CommentStatus::NOT_ACCEPTET => 'warning',
                        CommentStatus::FAILED => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('تاریخ')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('وضعیت')
                    ->options([
                        CommentStatus::NOT_ACCEPTET => 'در انتظار تایید',
                        CommentStatus::ACCEPTET => 'تایید شده',
                        CommentStatus::FAILED => 'رد شده',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('تایید')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record): bool => (int) $record->status !== CommentStatus::ACCEPTET)
                    ->action(fn ($record) => $record->update(['status' => CommentStatus::ACCEPTET])),

                EditAction::make(),

                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('هنوز دیدگاهی ثبت نشده')
            ->emptyStateDescription('دیدگاه‌های بازدیدکنندگان این‌جا برای تایید یا رد نمایش داده می‌شوند.');
    }
}

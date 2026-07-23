<?php

namespace App\Filament\Resources\Comments;

use App\Filament\Resources\Comments\Pages\EditComment;
use App\Filament\Resources\Comments\Pages\ListComments;
use App\Filament\Resources\Comments\Schemas\CommentForm;
use App\Filament\Resources\Comments\Tables\CommentsTable;
use App\Model\Comment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ دیدگاه‌ها — ابزارِ مدیریتیِ ادمین برای مُدِراسیونِ دیدگاه‌های بازدیدکنندگان
 * (تایید/رد/مشاهده/حذف) روی مدلِ زنده‌ی App\Model\Comment. دیدگاه‌ها توسطِ بازدیدکنندگان
 * ساخته می‌شوند؛ این‌جا فقط مُدِریت می‌شوند و ساختِ دیدگاه در دسترس نیست. storefront و مدل و
 * مهاجرت‌ها دست‌نخورده‌اند.
 */
class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'پشتیبانی';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'دیدگاه‌ها';

    protected static ?string $recordTitleAttribute = 'id';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return CommentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommentsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComments::route('/'),
            'edit' => EditComment::route('/{record}/edit'),
        ];
    }
}

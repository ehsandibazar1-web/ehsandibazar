<?php

namespace App\Filament\Resources\Videos;

use App\Filament\Resources\Videos\Pages\CreateVideo;
use App\Filament\Resources\Videos\Pages\EditVideo;
use App\Filament\Resources\Videos\Pages\ListVideos;
use App\Filament\Resources\Videos\Schemas\VideoForm;
use App\Filament\Resources\Videos\Tables\VideosTable;
use App\Model\Video;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

/**
 * مدیریتِ ویدیوها روی مدلِ زنده‌ی App\Model\Video. ستون‌های واقعی: title، url و رابطه‌ی چندریختیِ
 * videoable (مالکِ ویدیو) به‌همراه user_id. فیلدهای رابطه از فرم بیرون نگه داشته می‌شوند و
 * user_id هنگامِ ساخت از کاربرِ واردشده پر می‌شود. storefront دست‌نخورده؛ فقط ابزارِ مدیریتِ ادمین.
 */
class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static string|UnitEnum|null $navigationGroup = 'محتوا';

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationLabel = 'ویدیوها';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return VideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VideosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideos::route('/'),
            'create' => CreateVideo::route('/create'),
            'edit' => EditVideo::route('/{record}/edit'),
        ];
    }
}

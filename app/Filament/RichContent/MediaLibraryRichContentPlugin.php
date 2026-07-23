<?php

namespace App\Filament\RichContent;

use App\Filament\Forms\Components\MediaPickerInput;
use App\Models\Media;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\EditorCommand;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\HasToolbarButtons;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

/**
 * دکمه‌ی «کتابخانه‌ی رسانه» درونِ RichEditor — از همان پنجره‌ی انتخابِ رسانه‌ی یکپارچه‌ی کلِ CMS
 * استفاده می‌کند (App\Livewire\MediaPicker از طریقِ فیلدِ MediaPickerInput). دکمه یک اکشنِ کوچک باز
 * می‌کند که تنها محتوایش همان MediaPickerInput است + یک فیلدِ ALT؛ خودِ درج از مسیرِ رسمیِ RichEditor
 * (runCommands + editorSelection) انجام می‌شود تا انتخابِ متن حفظ شود و نیازی به JS build نباشد.
 *
 * درج بر اساسِ نوعِ رسانه:
 *   - تصویر → نودِ <img src alt> (از sanitize عبور می‌کند) — ویژگیِ اصلیِ این افزونه.
 *   - ویدئو/صوت/امبد → یک لینکِ تنهای پاراگراف (<p><a href>…</a></p>). نسخه‌ی فارسی رندرِ
 *     امبدِ عمومی ندارد؛ پس عمداً به‌جای پخش‌کننده‌ی click-to-load فقط یک لینکِ ساده درج می‌شود
 *     (تنزلِ آبرومندانه) و هیچ وابستگیِ سمتِ عمومی‌ای لازم نیست.
 *   - سند/زیپ/سایر → یک لینکِ دانلود (<a href> که از sanitize عبور می‌کند).
 *
 * ردگیریِ استفاده: عمداً URLِ فایلِ اصلی درج می‌شود (نه WebP) — MediaUsageScanner متن را با
 * disk_path تطبیق می‌دهد؛ با WebP، تصویرِ درون‌متنی به‌اشتباه «یتیم» می‌شد.
 */
class MediaLibraryRichContentPlugin implements HasToolbarButtons, RichContentPlugin
{
    public function __construct(protected string $directory = 'content-images') {}

    public static function make(string $directory = 'content-images'): static
    {
        return app(static::class, ['directory' => $directory]);
    }

    public function getTipTapPhpExtensions(): array
    {
        return [];
    }

    public function getTipTapJsExtensions(): array
    {
        return [];
    }

    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('mediaLibrary')
                ->label('کتابخانه‌ی رسانه')
                ->action()
                ->icon(Heroicon::Photo),
        ];
    }

    public function getEditorActions(): array
    {
        return [$this->action()];
    }

    public function getEnabledToolbarButtons(): array
    {
        return ['mediaLibrary'];
    }

    public function getDisabledToolbarButtons(): array
    {
        return [];
    }

    protected function action(): Action
    {
        $directory = $this->directory;

        return Action::make('mediaLibrary')
            ->label('کتابخانه‌ی رسانه')
            ->modalHeading('درج از کتابخانه‌ی رسانه')
            ->modalSubmitActionLabel('درج')
            ->modalWidth(Width::Large)
            ->schema([
                MediaPickerInput::make('media')
                    ->label('رسانه')
                    ->helperText('هر فایلی را از کتابخانه‌ی رسانه انتخاب کنید یا همان‌جا یک فایلِ تازه آپلود کنید. تصویرها درون‌متنی درج می‌شوند؛ ویدئو/صوت و سند به‌صورتِ یک لینک درج می‌شوند.')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // پیش‌پرکردنِ ALT از کتابخانه به‌عنوانِ پیش‌فرضِ *قابل‌ویرایش* — فقط وقتی
                        // ALT خالی است پر می‌شود تا متنی که نویسنده خودش نوشته پاک نشود.
                        if (filled($get('alt'))) {
                            return;
                        }
                        if ($alt = static::altPrefillFor(is_string($state) ? $state : null)) {
                            $set('alt', $alt);
                        }
                    })
                    ->uploadDirectory($directory),
                TextInput::make('embed_url')
                    ->label('یا نشانیِ یک ویدئو / امبد را بچسبانید')
                    ->helperText('یوتیوب، ویمئو، اینستاگرام یا تیک‌تاک — به‌صورتِ یک لینکِ ساده درج می‌شود.')
                    ->url(),
                TextInput::make('alt')
                    ->label('متنِ جایگزین (فقط تصویر — دسترس‌پذیری و سئوی تصویر)')
                    ->helperText('هنگامِ انتخابِ تصویر از کتابخانه پیش‌پر می‌شود — می‌توانید آن را برای این مقاله بومی‌سازی کنید.')
                    ->maxLength(1000),
            ])
            ->action(function (array $arguments, array $data, RichEditor $component): void {
                $embedUrl = trim((string) ($data['embed_url'] ?? ''));

                // یک URLِ embed برنده است؛ وگرنه رسانه‌ی انتخاب‌شده از کتابخانه
                if ($embedUrl !== '') {
                    $content = static::embedLinkHtml($embedUrl);
                } elseif ($media = Media::where('disk_path', $data['media'] ?? '')->first()) {
                    $content = static::insertContentFor($media, $data['alt'] ?? null);
                } else {
                    return;
                }

                $component->runCommands(
                    [EditorCommand::make('insertContent', arguments: [$content])],
                    editorSelection: $arguments['editorSelection'],
                );
            });
    }

    // ALTِ پیشنهادی برای پیش‌پرکردنِ فیلدِ modal از روی disk_pathِ انتخاب‌شده — فقط برای تصویری که
    // خودش ALT دارد. عمداً محض و static تا مستقل از ماشینِ فرمِ Filament تست شود (مثلِ insertContentFor).
    public static function altPrefillFor(?string $diskPath): ?string
    {
        if (! filled($diskPath)) {
            return null;
        }

        $media = Media::where('disk_path', $diskPath)->first();

        return ($media && $media->type === 'image' && filled($media->alt_text)) ? $media->alt_text : null;
    }

    /**
     * محتوایی که بر اساسِ نوعِ رسانه در ویرایشگر درج می‌شود — نودِ image برای تصویر (آرایه‌ی TipTap)
     * یا HTMLِ لینک برای بقیه. عمداً عمومی و محض است تا مستقل از ماشینِ اکشن تست شود.
     *
     * @return array<string, mixed>|string
     */
    public static function insertContentFor(Media $media, ?string $alt = null): array|string
    {
        if ($media->type === 'image') {
            return static::imageNode($media->url, ($alt !== null && trim($alt) !== '') ? $alt : $media->alt_text);
        }

        // ویدئو/صوتِ خودمیزبان: چون رندرِ امبدِ عمومی در نسخه‌ی فارسی نیست، یک لینکِ تنهای پاراگراف
        // با نامِ فایل به‌عنوانِ متنِ لینک درج می‌شود (تنزلِ آبرومندانه، بدونِ پخش‌کننده).
        if (in_array($media->type, ['video', 'audio'], true)) {
            return static::mediaLinkHtml($media->url, $media->original_name);
        }

        // اسناد/زیپ/سایر: لینکِ دانلودِ درون‌خطی (<a href> که از sanitize عبور می‌کند)
        return static::downloadLinkHtml($media->url, $media->original_name);
    }

    /**
     * ساختارِ نودِ image برای TipTap — که در نهایت به <img src alt> رندر می‌شود.
     *
     * @return array<string, mixed>
     */
    public static function imageNode(string $src, ?string $alt): array
    {
        return [
            'type' => 'image',
            'attrs' => [
                'src' => $src,
                'alt' => $alt,
            ],
        ];
    }

    // یک لینکِ دانلودِ ساده برای فایلِ غیرتصویری — <a href> از sanitize عبور می‌کند
    public static function downloadLinkHtml(string $url, string $filename): string
    {
        return '<a href="'.e($url).'" target="_blank" rel="noopener">📎 '.e($filename).'</a>';
    }

    // یک لینکِ تنهای پاراگراف به ویدئو/صوتِ خودمیزبان — نامِ فایل به‌عنوانِ متنِ لینک. جایگزینِ ساده‌ی
    // پخش‌کننده که در نسخه‌ی فارسی رندرِ امبدِ عمومی ندارد.
    public static function mediaLinkHtml(string $url, string $filename): string
    {
        return '<p><a href="'.e($url).'" target="_blank" rel="noopener">'.e($filename).'</a></p>';
    }

    // یک لینکِ تنهای پاراگراف به یک نشانیِ ویدئو/امبدِ چسبانده‌شده — متنِ لینک همان نشانی است.
    public static function embedLinkHtml(string $url): string
    {
        return '<p><a href="'.e($url).'" target="_blank" rel="noopener">'.e($url).'</a></p>';
    }
}

<?php

namespace App\Livewire;

use App\Filament\Concerns\InteractsWithMediaLibrary;
use App\Models\Media;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

/**
 * پنجره‌ی انتخابِ رسانه — یک کامپوننتِ Livewire که در چرومِ پنل یک‌بار سراسری mount می‌شود
 * (نگاه کنید به AdminPanelProvider render hook) و هر فیلدِ MediaPickerInput همین را باز می‌کند.
 *
 * منطقِ کتابخانه (پوشه/آپلود/فیلتر/جزئیات/حذف/بازتولید) دقیقاً همان trait ای است که صفحه‌ی
 * MediaLibrary استفاده می‌کند (App\Filament\Concerns\InteractsWithMediaLibrary) — پس صفر
 * دوباره‌کاری. این کلاس فقط رفتارِ «مودال + انتخاب-و-بازگشت به فیلد» را رویش می‌گذارد.
 *
 * قراردادِ رویدادها:
 *   - باز شدن: فیلد یک window CustomEvent «open-media-picker» با detail={target, onlyImages,
 *     uploadDirectory, initialType} می‌فرستد؛ ریشه‌ی این کامپوننت آن را می‌گیرد و openFor() را صدا می‌زند.
 *   - انتخاب: این کامپوننت یک window CustomEvent «media-picker-selected» با detail شاملِ
 *     {target, disk_path, url, type, mime_type, original_name} می‌فرستد؛ فقط فیلدی که target اش
 *     برابر باشد به آن گوش می‌دهد و مقدارش را ست می‌کند. مقدارِ ذخیره‌شده همان رشته‌ی disk_path
 *     است — عیناً همان چیزی که فیلدهای فعلی ذخیره می‌کنند، پس کاملاً backward-compatible.
 */
class MediaPicker extends Component
{
    use InteractsWithMediaLibrary;
    use WithFileUploads;

    public bool $isOpen = false;

    // فیلدی (statePath) که پنجره را باز کرده و نتیجه باید به آن برگردد
    public ?string $target = null;

    // فیلدهای تصویری این را true می‌فرستند تا پیکر خودکار فقط تصویر نشان دهد
    public bool $onlyImages = false;

    public string $viewMode = 'grid'; // grid | list

    // پیکر برخلافِ صفحه‌ی MediaLibrary (که فقط نام فایل را می‌گردد) روی همه‌ی این ستون‌ها جست‌وجو می‌کند
    protected function searchableColumns(): array
    {
        return ['original_name', 'alt_text', 'caption', 'description', 'mime_type'];
    }

    public function openFor(?string $target, bool $onlyImages = false, ?string $uploadDirectory = null, ?string $initialType = null): void
    {
        $this->target = $target;
        $this->onlyImages = $onlyImages;
        $this->uploadDirectory = $uploadDirectory ?: 'media/library';
        $this->isOpen = true;

        // شروعِ تمیز هر بار که باز می‌شود
        $this->selectedMediaId = null;
        $this->search = '';
        $this->currentFolderId = null;
        $this->resetPickerFilters();
        // فقط-تصویر: قفل روی image. در غیر این صورت اگر فیلد یک نوعِ پیش‌فرض داده باشد با همان نما باز می‌شود.
        $this->typeFilter = $onlyImages ? 'image' : ($initialType ?: 'all');
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->target = null;
        $this->selectedMediaId = null;
        $this->showNewFolderForm = false;
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['grid', 'list'], true) ? $mode : 'grid';
    }

    public function setTypeFilter(string $filter): void
    {
        // در حالتِ فقط-تصویر، فیلترِ نوع قفل روی image می‌ماند
        if ($this->onlyImages) {
            return;
        }

        $this->typeFilter = $filter;
        $this->selectedMediaId = null;
    }

    private function resetPickerFilters(): void
    {
        $this->onlyUnused = false;
        $this->onlyOrphaned = false;
        $this->onlyMissingAlt = false;
        $this->onlyLarge = false;
    }

    // «درجِ فوری» — دابل‌کلیک یا دکمه‌ی «استفاده از این فایل». مقدار را به فیلدِ فراخوان برمی‌گرداند و پنجره را می‌بندد.
    public function chooseAndReturn(int $mediaId): void
    {
        $media = Media::find($mediaId);

        if (! $media) {
            return;
        }

        if ($this->onlyImages && $media->type !== 'image') {
            Notification::make()
                ->warning()
                ->title('این فیلد فقط تصویر می‌پذیرد')
                ->body('لطفاً یک فایلِ تصویری انتخاب کنید.')
                ->send();

            return;
        }

        // یک window CustomEvent قطعی می‌فرستیم تا فیلد — که با Alpine به window گوش می‌دهد — بی‌ابهام آن را بگیرد
        $payload = json_encode([
            'target' => $this->target,
            'disk_path' => $media->disk_path,
            'url' => $media->url,
            'type' => $media->type,
            'mime_type' => $media->mime_type,
            'original_name' => $media->original_name,
        ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_UNESCAPED_SLASHES);

        $this->js("window.dispatchEvent(new CustomEvent('media-picker-selected', { detail: {$payload} }))");

        $this->close();
    }

    // آیکونِ نوعِ فایل برای رسانه‌های غیرتصویری — نگاشتِ ساده‌ی emoji. تصاویر خودشان تامبنیل دارند.
    public static function icon(Media $media): string
    {
        return match (true) {
            $media->type === 'video' => '🎬',
            $media->type === 'audio' => '🎵',
            $media->mime_type === 'application/pdf' => '📕',
            $media->mime_type === 'application/zip' => '🗜️',
            $media->type === 'document' => '📄',
            default => '📎',
        };
    }

    public function render()
    {
        return view('livewire.media-picker');
    }
}

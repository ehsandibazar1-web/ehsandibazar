<?php

namespace App\Filament\Forms\Components;

use App\Models\Media;
use Closure;
use Filament\Forms\Components\Field;

/**
 * فیلدِ انتخابِ رسانه — به‌جای یک آپلودِ ساده، تصویر را از کتابخانه‌ی رسانه انتخاب می‌کند.
 *
 * مقدارِ ذخیره‌شده همان رشته‌ی disk_path است — عیناً همان چیزی که FileUpload های فعلی ذخیره
 * می‌کردند — پس هر خواننده‌ی موجود (BlogController، تمپلیت‌های عمومی، …) و هر ردیفِ محتوای
 * موجود بدونِ تغییر کار می‌کند. فیلد فقط ویجت را عوض می‌کند، نه شکلِ داده را.
 * storefront همچنان image_path را با asset('storage/'.$image_path) می‌خواند.
 *
 * باز کردن: با یک window CustomEvent «open-media-picker» پنجره‌ی سراسریِ App\Livewire\MediaPicker
 * را باز می‌کند؛ انتخاب از طریقِ «media-picker-selected» برمی‌گردد (نگاه کنید به آن کامپوننت).
 */
class MediaPickerInput extends Field
{
    protected string $view = 'filament.forms.components.media-picker-input';

    // فقط تصویر؟ فیلدهای تصویرِ شاخص این را true می‌کنند؛ پیکر خودش به تصویر فیلتر می‌شود
    protected bool|Closure $onlyImages = false;

    // پوشه‌ای که آپلودِ تازه از درونِ پیکر در آن بنشیند — پیش‌فرض media/library، ولی مثلا تصویرِ
    // شاخصِ مقاله می‌تواند 'articles' بدهد تا ردگیریِ «یتیم» (isInSystemAttachedDirectory) دست‌نخورده بماند
    protected string|Closure $uploadDirectory = 'media/library';

    // نوعِ پیش‌فرضی که پیکر با آن باز می‌شود — یک نمای اولیه، نه یک قفل. برای فیلدهای فقط-تصویر بی‌اثر است.
    protected string|Closure|null $initialType = null;

    public function onlyImages(bool|Closure $condition = true): static
    {
        $this->onlyImages = $condition;

        return $this;
    }

    public function uploadDirectory(string|Closure $directory): static
    {
        $this->uploadDirectory = $directory;

        return $this;
    }

    public function initialType(string|Closure|null $type): static
    {
        $this->initialType = $type;

        return $this;
    }

    public function getInitialType(): ?string
    {
        return $this->evaluate($this->initialType);
    }

    public function isOnlyImages(): bool
    {
        return (bool) $this->evaluate($this->onlyImages);
    }

    public function getUploadDirectory(): string
    {
        return (string) $this->evaluate($this->uploadDirectory);
    }

    // رکوردِ Media متناظر با مقدارِ فعلی (disk_path) — برای نمایشِ تامبنیل/نامِ فایل در ویجت.
    // اگر مقدار به فایلی اشاره کند که ردیفِ Media ندارد (تصویرِ پیش از DAM)، null برمی‌گردد و
    // ویجت به نمایشِ خودِ مسیر برمی‌گردد — چیزی نمی‌شکند.
    public function getSelectedMedia(): ?Media
    {
        $state = $this->getState();

        if (blank($state)) {
            return null;
        }

        return Media::where('disk_path', $state)->first();
    }
}

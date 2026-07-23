@php
    $statePath = $getStatePath();
    $media = $getSelectedMedia();
    $state = $getState();
    $onlyImages = $isOnlyImages();
    $uploadDir = $getUploadDirectory();
    $initialType = $getInitialType();
    $previewUrl = $media && $media->type === 'image'
        ? $media->thumbnail_url
        : ($onlyImages && filled($state) ? \Illuminate\Support\Facades\Storage::disk('public')->url($state) : null);

    // پیش‌نمایشِ جایگزین (تصویرِ فعلیِ مقاله‌های قدیمی از رابطه‌ی image) — فقط وقتی هنوز چیزی در
    // این فیلد انتخاب نشده. صرفاً نمایشی است؛ مقدارِ فیلد خالی می‌ماند و چیزی در DB نوشته نمی‌شود.
    $fallbackPreview = $getFallbackPreviewUrl();
    $showingFallback = ! $previewUrl && blank($state) && filled($fallbackPreview);
    if ($showingFallback) {
        $previewUrl = $fallbackPreview;
    }
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    {{--
        فیلد فقط ویجت است — مقدار (disk_path) با $wire.set ست می‌شود، پس هیچ ذخیره‌ی سفارشی‌ای لازم
        نیست و شکلِ داده دست‌نخورده می‌ماند. مقادیرِ باز کردن/شنیدن در x-data می‌نشینند تا عبارتِ
        inline پیچیده و شکننده نشود.
    --}}
    <div
        dir="rtl"
        x-data="{
            pickTarget: @js($statePath),
            onlyImages: @js($onlyImages),
            uploadDir: @js($uploadDir),
            initialType: @js($initialType),
            openPicker() {
                window.dispatchEvent(new CustomEvent('open-media-picker', {
                    detail: { target: this.pickTarget, onlyImages: this.onlyImages, uploadDirectory: this.uploadDir, initialType: this.initialType },
                }));
            },
        }"
        x-on:media-picker-selected.window="if ($event.detail.target === pickTarget) { $wire.set(pickTarget, $event.detail.disk_path) }"
    >
        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap">
            @if($previewUrl)
                <div style="display:flex;flex-direction:column;gap:.3rem">
                    <img src="{{ $previewUrl }}" alt="{{ $media?->alt_text }}"
                        style="width:96px;height:96px;object-fit:cover;border-radius:.55rem;border:1px solid rgb(229 231 235)">
                    @if($showingFallback)
                        <span style="font-size:.72rem;color:#6b7280">تصویرِ فعلیِ مقاله</span>
                    @endif
                </div>
            @elseif(filled($state))
                <div style="display:flex;align-items:center;gap:.4rem;padding:.5rem .7rem;border:1px solid rgb(229 231 235);border-radius:.55rem;font-size:.82rem;color:#374151">
                    📎 <span style="max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $media?->original_name ?? basename($state) }}</span>
                </div>
            @else
                <div style="font-size:.82rem;color:#9ca3af">فایلی انتخاب نشده است</div>
            @endif

            <div style="display:flex;gap:.5rem;flex-wrap:wrap">
                <x-filament::button type="button" size="sm" icon="heroicon-o-photo" x-on:click="openPicker()">
                    {{ (filled($state) || $showingFallback) ? 'تغییر' : ($onlyImages ? 'انتخابِ تصویر' : 'انتخاب از کتابخانه‌ی رسانه') }}
                </x-filament::button>

                @if(filled($state))
                    <x-filament::button type="button" size="sm" color="gray" x-on:click="$wire.set(pickTarget, null)">
                        حذف
                    </x-filament::button>
                @endif
            </div>
        </div>
    </div>
</x-dynamic-component>

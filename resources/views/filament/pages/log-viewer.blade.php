<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">انتهای فایلِ لاگ</x-slot>
        <x-slot name="description">
            فقط-خواندنی — آخرین بخشِ storage/logs/laravel.log (حداکثر ۶۴ کیلوبایت).
            @if ($fileInfo) حجمِ فایل: {{ $fileInfo }} @endif
        </x-slot>

        <div class="mb-3">
            <x-filament::button wire:click="refreshLog" icon="heroicon-o-arrow-path" color="gray" wire:loading.attr="disabled">
                تازه‌سازی
            </x-filament::button>
            <span wire:loading class="text-xs text-gray-400 ms-2">در حالِ خواندن…</span>
        </div>

        @if ($content === '')
            <p class="text-sm text-success-600 dark:text-success-400">فایلِ لاگ خالی است یا وجود ندارد — یعنی خطایی ثبت نشده. 🎉</p>
        @else
            <pre dir="ltr" class="max-h-[36rem] overflow-auto whitespace-pre-wrap rounded bg-gray-950 p-3 text-[11px] leading-5 text-gray-200">{{ $content }}</pre>
        @endif
    </x-filament::section>
</x-filament-panels::page>

<x-filament-panels::page>
    <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
        بعد از هر «Update from Remote» روی هاست، دکمهٔ <span class="font-semibold">«پاک‌سازی و بهینه‌سازی»</span> را بزنید
        تا کدِ جدید دیده شود. «اجرای Migration» جدول‌های تازه را می‌سازد (روی سایتِ اصلی اول بکاپ بگیرید).
    </div>

    @if ($output !== null)
        <x-filament::section>
            <x-slot name="heading">
                خروجی @if ($lastAction)<span class="text-xs font-normal text-gray-400">({{ $lastAction }})</span>@endif
            </x-slot>
            <pre dir="ltr" class="max-h-96 overflow-auto whitespace-pre-wrap rounded bg-gray-950 p-4 text-xs leading-6 text-gray-100">{{ $output }}</pre>
        </x-filament::section>
    @else
        <x-filament::section>
            <div class="py-8 text-center text-gray-400">
                یکی از دکمه‌های بالا را بزنید تا خروجی اینجا نمایش داده شود.
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>

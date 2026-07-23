<x-filament-panels::page>
    <x-filament::section>
        <div dir="rtl" style="line-height: 1.9;">
            <strong>توجه:</strong> این تنظیمات در یک انبارِ مستقل ذخیره می‌شوند و
            <strong>هنوز به قالبِ سایتِ زنده متصل نیستند</strong>. تغییرِ این مقادیر چیزی روی سایتِ
            فعلی عوض نمی‌کند — اتصال به قالبِ سایت یک قدمِ بعدیِ جداست.
        </div>
    </x-filament::section>

    <form wire:submit="save">
        {{ $this->form }}

        <div style="margin-top: 1.5rem;">
            <x-filament::button type="submit">
                ذخیره‌ی تنظیمات
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>

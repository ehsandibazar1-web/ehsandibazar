<x-filament-panels::page>
    <div class="space-y-6" dir="rtl">

        {{-- ===================== تنظیمات هوش مصنوعی ===================== --}}
        <x-filament::section>
            <x-slot name="heading">تنظیمات هوش مصنوعی</x-slot>
            <x-slot name="description">
                کلید API خود را از console.anthropic.com بگیرید و اینجا وارد کنید. کلید به‌صورت
                رمزنگاری‌شده در دیتابیس ذخیره می‌شود و در گیت‌هاب قرار نمی‌گیرد.
            </x-slot>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">کلید API</label>
                    <input
                        type="password"
                        wire:model="apiKey"
                        placeholder="sk-ant-..."
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm text-sm"
                        dir="ltr"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">مدل هوش مصنوعی</label>
                    <select
                        wire:model="model"
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm text-sm"
                    >
                        @foreach ($this->modelOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <x-filament::button wire:click="saveSettings" icon="heroicon-o-check">
                    ذخیره تنظیمات
                </x-filament::button>
            </div>
        </x-filament::section>

        {{-- ===================== تولید مقاله ===================== --}}
        <x-filament::section>
            <x-slot name="heading">تولید مقاله جدید</x-slot>
            <x-slot name="description">
                موضوع یا عنوان مقاله را بنویسید تا هوش مصنوعی یک مقاله‌ی فارسیِ سئوشده تولید کند.
            </x-slot>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">موضوع مقاله</label>
                    <textarea
                        wire:model="topic"
                        rows="2"
                        placeholder="مثال: فواید تمرین دفاع شخصی برای بانوان"
                        class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm text-sm"
                    ></textarea>
                </div>

                <div class="flex items-center gap-3">
                    <x-filament::button
                        wire:click="generate"
                        wire:loading.attr="disabled"
                        wire:target="generate"
                        icon="heroicon-o-sparkles"
                    >
                        <span wire:loading.remove wire:target="generate">تولید مقاله</span>
                        <span wire:loading wire:target="generate">در حال تولید... (چند ثانیه صبر کنید)</span>
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>

        {{-- ===================== پیش‌نمایش خروجی ===================== --}}
        @if ($hasResult)
            <x-filament::section>
                <x-slot name="heading">پیش‌نمایش مقاله‌ی تولیدشده</x-slot>
                <x-slot name="description">
                    قبل از ذخیره می‌توانید متن را ویرایش کنید. پس از ذخیره، مقاله به‌صورت
                    «پیش‌نویس» (منتشرنشده) در بخش مقالات قرار می‌گیرد.
                </x-slot>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">عنوان</label>
                        <input
                            type="text"
                            wire:model="genTitle"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm text-sm"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">نامک (slug)</label>
                        <input
                            type="text"
                            wire:model="genSlug"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm text-sm"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">توضیحات متا (برای گوگل)</label>
                        <textarea
                            wire:model="genMeta"
                            rows="2"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm text-sm"
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">متن مقاله (HTML)</label>
                        <textarea
                            wire:model="genBody"
                            rows="14"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm text-sm font-mono"
                            dir="rtl"
                        ></textarea>
                    </div>

                    @if (! empty($genFaq))
                        <div>
                            <label class="block text-sm font-medium mb-1">سوالات متداول</label>
                            <div class="rounded-lg border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($genFaq as $item)
                                    <div class="p-3">
                                        <p class="font-semibold text-sm">{{ $item['question'] ?? '' }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $item['answer'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-3 pt-2">
                        <x-filament::button
                            wire:click="saveDraft"
                            color="success"
                            icon="heroicon-o-document-check"
                        >
                            ذخیره به‌عنوان پیش‌نویس
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>
        @endif

    </div>
</x-filament-panels::page>

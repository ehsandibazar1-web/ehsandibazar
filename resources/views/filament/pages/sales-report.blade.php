<x-filament-panels::page>
    <div class="space-y-6">

        <x-filament::section>
            <x-slot name="heading">فیلترهای گزارش</x-slot>
            <x-slot name="description">
                فقط سفارش‌های «ارسال‌شده» شمرده می‌شوند (همان مبنای گزارش‌گیریِ پنلِ قدیمی). تاریخ‌ها شمسی‌اند.
            </x-slot>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">دسته‌بندی</span>
                    <select wire:model="categoryId" class="fi-input block w-full rounded-lg border-gray-300 text-sm shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                        <option value="">همه‌ی دسته‌ها</option>
                        @foreach ($this->categoryOptions() as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">محصول</span>
                    <select wire:model="productId" class="fi-input block w-full rounded-lg border-gray-300 text-sm shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                        <option value="">همه‌ی محصولات</option>
                        @foreach ($this->productOptions() as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">از تاریخ (شمسی)</span>
                    <input type="text" dir="ltr" placeholder="1404/05/01" wire:model="startDate"
                           class="fi-input block w-full rounded-lg border-gray-300 text-sm shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white" />
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">تا تاریخ (شمسی)</span>
                    <input type="text" dir="ltr" placeholder="1404/05/30" wire:model="endDate"
                           class="fi-input block w-full rounded-lg border-gray-300 text-sm shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white" />
                </label>
            </div>

            <div class="mt-4">
                <x-filament::button wire:click="runReport" icon="heroicon-o-document-chart-bar" wire:loading.attr="disabled">
                    اجرای گزارش
                </x-filament::button>
                <span wire:loading class="text-xs text-gray-400 ms-2">در حالِ محاسبه…</span>
            </div>
        </x-filament::section>

        @if ($ran)
            <x-filament::section>
                <x-slot name="heading">نتیجه ({{ number_format(count($rows)) }} ردیف)</x-slot>

                @if (count($rows) === 0)
                    <p class="text-sm text-gray-500 dark:text-gray-400">در این بازه/فیلتر، سفارشِ ارسال‌شده‌ای پیدا نشد.</p>
                @else
                    <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="rounded-lg bg-success-50 p-3 text-center dark:bg-success-500/10">
                            <div class="text-xs text-gray-500 dark:text-gray-400">جمعِ تعداد</div>
                            <div class="text-lg font-bold text-success-700 dark:text-success-400">{{ number_format($totals['count']) }}</div>
                        </div>
                        <div class="rounded-lg bg-primary-50 p-3 text-center dark:bg-primary-500/10">
                            <div class="text-xs text-gray-500 dark:text-gray-400">جمعِ مبلغ (تومان)</div>
                            <div class="text-lg font-bold text-primary-700 dark:text-primary-400">{{ number_format($totals['amount']) }}</div>
                        </div>
                        <div class="rounded-lg bg-warning-50 p-3 text-center dark:bg-warning-500/10">
                            <div class="text-xs text-gray-500 dark:text-gray-400">جمعِ تخفیف (تومان)</div>
                            <div class="text-lg font-bold text-warning-700 dark:text-warning-400">{{ number_format($totals['discount']) }}</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 text-right dark:border-white/10">
                                    <th class="px-3 py-2 font-medium text-gray-500 dark:text-gray-400">محصول</th>
                                    <th class="px-3 py-2 font-medium text-gray-500 dark:text-gray-400">تعداد</th>
                                    <th class="px-3 py-2 font-medium text-gray-500 dark:text-gray-400">مبلغ (تومان)</th>
                                    <th class="px-3 py-2 font-medium text-gray-500 dark:text-gray-400">تخفیف</th>
                                    <th class="px-3 py-2 font-medium text-gray-500 dark:text-gray-400">تاریخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    <tr class="border-b border-gray-100 dark:border-white/5">
                                        <td class="px-3 py-2">{{ $row['product'] }}</td>
                                        <td class="px-3 py-2">{{ number_format($row['count']) }}</td>
                                        <td class="px-3 py-2">{{ number_format($row['amount']) }}</td>
                                        <td class="px-3 py-2">{{ number_format($row['discount']) }}</td>
                                        <td class="px-3 py-2 text-xs text-gray-500">{{ $row['date'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-filament::section>
        @endif

    </div>
</x-filament-panels::page>

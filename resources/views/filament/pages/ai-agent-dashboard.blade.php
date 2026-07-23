<x-filament-panels::page>
    <div class="mb-4 flex flex-wrap items-center gap-3">
        <x-filament::button wire:click="refresh" icon="heroicon-o-arrow-path" color="gray" size="sm">
            بازبینیِ دوباره
        </x-filament::button>
        <span class="text-sm text-gray-500 dark:text-gray-400">
            این عامل هیچ محتوایی را خودکار تغییر نمی‌دهد — فقط مشکلات را رتبه‌بندی می‌کند و شما دستی اصلاح می‌کنید.
        </span>
    </div>

    {{-- کارت‌های خلاصه --}}
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="text-2xl font-bold text-danger-600 dark:text-danger-400">{{ $this->totalIssues }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">مجموعِ مشکلات</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="text-2xl font-bold text-warning-600 dark:text-warning-400">{{ $this->affectedCount }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">محتوای نیازمندِ توجه</div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <a href="{{ \App\Filament\Pages\SeoCenter::getUrl() }}" class="text-sm font-semibold text-primary-600 hover:underline dark:text-primary-400">
                مرور بر اساسِ دسته →
            </a>
            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">در SEO Center</div>
        </div>
    </div>

    {{-- محتوای نیازمندِ بیشترین توجه --}}
    @php $items = $this->contentHealth; @endphp
    @if (empty($items))
        <x-filament::section>
            <div class="py-10 text-center text-gray-500 dark:text-gray-400">
                🎉 هیچ مشکلی پیدا نشد — همه‌ی محتوا سالم است!
            </div>
        </x-filament::section>
    @else
        <div class="space-y-3">
            @foreach ($items as $item)
                <x-filament::section>
                    <x-slot name="heading">
                        <span class="inline-flex items-center gap-2">
                            <span class="rounded-full bg-danger-100 px-2 py-0.5 text-xs font-bold text-danger-700 dark:bg-danger-500/15 dark:text-danger-300">
                                {{ $item['count'] }} مشکل
                            </span>
                            <span class="text-xs font-normal text-gray-400">{{ $item['type'] === 'article' ? 'مقاله' : 'صفحه' }}</span>
                            {{ $item['title'] }}
                        </span>
                    </x-slot>

                    <x-slot name="afterHeader">
                        @if ($item['edit_url'])
                            <x-filament::link :href="$item['edit_url']" size="sm" icon="heroicon-o-pencil-square">
                                اصلاح دستی
                            </x-filament::link>
                        @endif
                    </x-slot>

                    <ul class="list-inside list-disc space-y-1 text-sm text-gray-600 dark:text-gray-300">
                        @foreach ($item['issues'] as $issue)
                            <li>{{ $issue }}</li>
                        @endforeach
                    </ul>
                </x-filament::section>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>

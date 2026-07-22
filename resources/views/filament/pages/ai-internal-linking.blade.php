<x-filament-panels::page>
    <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
        فرصت‌های لینکِ داخلی: جاهایی که یک محتوا عنوانِ محتوای دیگری را ذکر کرده اما هنوز به آن لینک
        نداده است. لینکِ آماده را کپی کنید و در ویرایشگرِ همان مقاله/صفحه، روی عبارتِ لنگر اعمال کنید.
        <span class="font-semibold">هیچ محتوایی خودکار تغییر نمی‌کند.</span>
        — مجموع: {{ $this->total }} پیشنهاد.
    </div>

    @php $groups = $this->groupedSuggestions; @endphp

    @if (empty($groups))
        <x-filament::section>
            <div class="py-8 text-center text-gray-500 dark:text-gray-400">
                فعلاً فرصتِ لینکِ داخلیِ جدیدی پیدا نشد. عالی‌ست! 🎉
            </div>
        </x-filament::section>
    @else
        <div class="space-y-4">
            @foreach ($groups as $group)
                <x-filament::section>
                    <x-slot name="heading">
                        <span class="text-xs font-normal text-gray-400">
                            {{ $group['source_type'] === 'article' ? 'مقاله' : 'صفحه' }}
                        </span>
                        {{ $group['source_title'] }}
                    </x-slot>

                    <x-slot name="afterHeader">
                        <div class="flex items-center gap-2">
                            @if ($group['edit_url'])
                                <x-filament::link :href="$group['edit_url']" size="sm" icon="heroicon-o-pencil-square">
                                    ویرایش
                                </x-filament::link>
                            @endif
                            <x-filament::link :href="$group['view_url']" target="_blank" size="sm" icon="heroicon-o-eye" color="gray">
                                نمایش
                            </x-filament::link>
                        </div>
                    </x-slot>

                    <ul class="divide-y divide-gray-100 dark:divide-white/10">
                        @foreach ($group['links'] as $link)
                            <li class="flex flex-col gap-1 py-2 sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-sm">
                                    لینک به:
                                    <a href="{{ $link['target_url'] }}" target="_blank" class="font-semibold text-primary-600 hover:underline dark:text-primary-400">
                                        {{ $link['anchor'] }}
                                    </a>
                                    <span class="text-xs text-gray-400">({{ $link['target_type'] === 'article' ? 'مقاله' : 'صفحه' }})</span>
                                </div>
                                <code
                                    class="cursor-pointer select-all rounded bg-gray-100 px-2 py-1 text-xs text-gray-700 dark:bg-white/5 dark:text-gray-300"
                                    dir="ltr"
                                    title="برای انتخاب کلیک کنید"
                                >{{ $link['link_html'] }}</code>
                            </li>
                        @endforeach
                    </ul>
                </x-filament::section>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>

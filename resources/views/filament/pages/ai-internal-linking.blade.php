<x-filament-panels::page>
    <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
        فرصت‌های لینکِ داخلی: جاهایی که یک محتوا عنوانِ محتوای دیگری را ذکر کرده اما هنوز به آن لینک
        نداده است. با دکمهٔ «اعمال» پیش‌نمایشِ قبل/بعد را می‌بینید و پس از تأیید، لینک در بدنه گذاشته
        می‌شود (فقط همان یک عبارت). یا لینکِ آمادهٔ کنارِ هر ردیف را کپی و دستی اعمال کنید.
        — مجموع: {{ $this->total }} پیشنهاد.
    </div>

    {{-- پنلِ پیش‌نمایش/تأیید --}}
    @if ($pending)
        <x-filament::section class="mb-4 ring-2 ring-primary-500">
            <x-slot name="heading">پیش‌نمایشِ افزودنِ لینک به «{{ $pending['source_title'] }}»</x-slot>

            <div class="space-y-3 text-sm">
                <div>
                    <div class="mb-1 font-semibold text-gray-500 dark:text-gray-400">قبل:</div>
                    <div class="rounded bg-gray-50 p-2 leading-7 dark:bg-white/5">{!! $pending['preview_before'] !!}</div>
                </div>
                <div>
                    <div class="mb-1 font-semibold text-gray-500 dark:text-gray-400">بعد:</div>
                    <div class="rounded bg-gray-50 p-2 leading-7 dark:bg-white/5">{!! $pending['preview_after'] !!}</div>
                </div>
            </div>

            <x-slot name="footer">
                <div class="flex items-center gap-2">
                    <x-filament::button wire:click="confirmApply" icon="heroicon-o-check" color="success">
                        تأیید و افزودنِ لینک
                    </x-filament::button>
                    <x-filament::button wire:click="cancelPreview" color="gray" outlined>
                        انصراف
                    </x-filament::button>
                </div>
            </x-slot>
        </x-filament::section>
    @endif

    {{-- لینک‌های داخلیِ شکسته: به محتوای منتشرنشده/حذف‌شده --}}
    @php $broken = $this->brokenLinks; @endphp
    @if (! empty($broken))
        <x-filament::section collapsible collapsed class="mb-4 ring-1 ring-danger-300 dark:ring-danger-500/40">
            <x-slot name="heading">
                <span class="text-danger-600 dark:text-danger-400">🔗 لینک‌های شکسته</span>
                <span class="text-xs font-normal text-gray-400">({{ count($broken) }} مورد)</span>
            </x-slot>
            <x-slot name="description">
                این لینک‌ها به مقاله/صفحه‌ای اشاره می‌کنند که منتشر نیست یا وجود ندارد — روی سایت ۴۰۴ می‌دهند و به سئو آسیب می‌زنند. مبدأ را ویرایش و لینک را اصلاح/حذف کنید.
            </x-slot>

            <ul class="divide-y divide-gray-100 dark:divide-white/10">
                @foreach ($broken as $bl)
                    <li class="flex flex-col gap-1 py-2 text-sm sm:flex-row sm:items-center sm:justify-between">
                        <span>
                            <span class="text-xs text-gray-400">{{ $bl['source_type'] === 'article' ? 'مقاله' : 'صفحه' }}</span>
                            {{ $bl['source_title'] }}
                            <span class="text-gray-400">→</span>
                            <code dir="ltr" class="rounded bg-danger-50 px-1 text-xs text-danger-700 dark:bg-danger-500/10 dark:text-danger-300">/{{ $bl['target_type'] }}/{{ $bl['target_slug'] }}</code>
                        </span>
                        @if ($bl['edit_url'])
                            <x-filament::link :href="$bl['edit_url']" size="sm" icon="heroicon-o-pencil-square">ویرایشِ مبدأ</x-filament::link>
                        @endif
                    </li>
                @endforeach
            </ul>
        </x-filament::section>
    @endif

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
                            <li class="flex flex-col gap-2 py-2 sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-sm">
                                    لینک به:
                                    <a href="{{ $link['target_url'] }}" target="_blank" class="font-semibold text-primary-600 hover:underline dark:text-primary-400">
                                        {{ $link['anchor'] }}
                                    </a>
                                    <span class="text-xs text-gray-400">({{ $link['target_type'] === 'article' ? 'مقاله' : 'صفحه' }})</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <x-filament::button
                                        wire:click="preview({{ $link['index'] }})"
                                        wire:loading.attr="disabled"
                                        size="sm"
                                        icon="heroicon-o-link"
                                    >
                                        اعمال
                                    </x-filament::button>
                                    <code
                                        class="cursor-pointer select-all rounded bg-gray-100 px-2 py-1 text-xs text-gray-700 dark:bg-white/5 dark:text-gray-300"
                                        dir="ltr"
                                        title="برای انتخاب کلیک کنید"
                                    >{{ $link['link_html'] }}</code>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </x-filament::section>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>

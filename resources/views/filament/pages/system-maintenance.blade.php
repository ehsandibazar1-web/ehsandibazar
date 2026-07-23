<x-filament-panels::page>
    <div class="space-y-6">

        {{-- ۱. ابزارهای استقرار --}}
        <x-filament::section>
            <x-slot name="heading">ابزارهای استقرار</x-slot>
            <x-slot name="description">
                بعد از اینکه کدِ جدید روی سرور مستقر شد این‌ها را بزنید. اجرای دوباره وقتی چیزی تغییر نکرده هیچ ضرری ندارد.
            </x-slot>

            <div class="flex flex-wrap items-center gap-2">
                <x-filament::button
                    wire:click="runMigrate"
                    wire:confirm="دستورِ به‌روزرسانیِ دیتابیس (migrate) اجرا می‌شود. روی سایتِ اصلی اول از دیتابیس بکاپ بگیرید. ادامه؟"
                    icon="heroicon-o-circle-stack"
                    color="warning"
                    wire:loading.attr="disabled"
                >
                    اجرای به‌روزرسانیِ دیتابیس
                </x-filament::button>

                <x-filament::button wire:click="runOptimize" icon="heroicon-o-arrow-path" color="gray" wire:loading.attr="disabled">
                    پاک‌سازیِ کش
                </x-filament::button>

                <x-filament::button wire:click="publishDesign" icon="heroicon-o-paper-airplane" color="gray" wire:loading.attr="disabled">
                    انتشارِ فایل‌های طراحی
                </x-filament::button>

                <x-filament::button wire:click="runPublishDue" icon="heroicon-o-rocket-launch" color="success" wire:loading.attr="disabled">
                    انتشارِ مقاله‌های سررسیده
                </x-filament::button>

                <span wire:loading class="text-xs text-gray-400">در حالِ اجرا…</span>
            </div>

            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                «انتشارِ فایل‌های طراحی» فایل‌های ظاهریِ پنل و symlinkِ رسانه را تازه می‌کند — بعد از هر «Update from Remote» بزنید.
            </p>

            <div class="mt-4 rounded-lg border border-primary-200 bg-primary-50 p-3 text-xs dark:border-primary-500/30 dark:bg-primary-500/10">
                <div class="mb-1 font-semibold text-primary-700 dark:text-primary-300">انتشارِ خودکارِ مقاله‌های زمان‌بندی‌شده</div>
                <p class="mb-2 text-gray-600 dark:text-gray-400">
                    برای اینکه مقاله‌های «زمان‌بندی‌شده» سرِ ساعت خودکار منتشر شوند (بدونِ نیاز به زدنِ دستیِ دکمه‌ی بالا)،
                    این آدرس را به یک سرویسِ رایگانِ زمان‌بند (مثلِ cron-job.org) بدهید تا هر ۵ دقیقه یک‌بار بازش کند:
                </p>
                <code dir="ltr" class="block select-all overflow-x-auto rounded bg-white px-2 py-1 text-[11px] text-gray-800 dark:bg-black/30 dark:text-gray-200">{{ $this->pingerUrl() }}</code>
                <p class="mt-2 text-gray-500 dark:text-gray-500">
                    این آدرس با یک توکنِ مخفی محافظت می‌شود. اگر cPanel شما Cron Job دارد، به‌جای این می‌توانید
                    <code dir="ltr" class="text-[11px]">php artisan schedule:run</code> را هر دقیقه اجرا کنید.
                </p>
            </div>

            @if ($output !== null)
                <pre dir="ltr" class="mt-4 max-h-72 overflow-auto whitespace-pre-wrap rounded bg-gray-950 p-3 text-xs leading-6 text-gray-100">{{ $output }}</pre>
            @endif
        </x-filament::section>

        {{-- ۲. پشتیبان‌گیریِ دیتابیس --}}
        <x-filament::section>
            <x-slot name="heading">پشتیبان‌گیریِ دیتابیس</x-slot>
            <x-slot name="description">
                دیتابیس همه‌چیزِ سایت را نگه می‌دارد — مقاله‌ها، صفحه‌ها و همه‌ی تنظیمات. آخرین {{ $backupInfo['keep'] }} نسخه روی سرور نگه داشته می‌شود. گاهی «دانلودِ آخرین پشتیبان» را هم بزنید تا یک نسخه روی کامپیوترِ خودتان باشد.
            </x-slot>

            @if ($backupInfo['last'])
                <p class="mb-3 text-sm text-success-600 dark:text-success-400">
                    <x-filament::icon icon="heroicon-o-check-circle" class="inline h-4 w-4" />
                    آخرین پشتیبان: <span class="font-semibold">{{ \Illuminate\Support\Carbon::createFromTimestamp($backupInfo['last']['time'])->diffForHumans() }}</span>
                    ({{ $this->humanSize($backupInfo['last']['size']) }}) — {{ $backupInfo['count'] }} نسخه روی سرور.
                </p>
            @else
                <p class="mb-3 text-sm text-gray-500 dark:text-gray-400">هنوز پشتیبانی ساخته نشده است.</p>
            @endif

            <div class="flex flex-wrap items-center gap-2">
                <x-filament::button wire:click="backupNow" icon="heroicon-o-inbox-arrow-down" color="warning" wire:loading.attr="disabled" wire:target="backupNow">
                    پشتیبان‌گیری اکنون
                </x-filament::button>

                @if ($backupInfo['last'])
                    <x-filament::button wire:click="downloadLatest" icon="heroicon-o-arrow-down-tray" color="gray">
                        دانلودِ آخرین پشتیبان
                    </x-filament::button>
                @endif

                <span wire:loading wire:target="backupNow" class="text-xs text-gray-400">در حالِ ساختِ پشتیبان…</span>
            </div>
        </x-filament::section>

        {{-- ۳. چکِ لینکِ رسانه --}}
        <x-filament::section>
            <x-slot name="heading">لینکِ رسانه (دسترسیِ عمومیِ فایل‌ها)</x-slot>
            <x-slot name="description">
                بررسی می‌کند که تصاویر و فایل‌های آپلودشده واقعاً روی وب در دسترس‌اند — یک فایلِ آزمایشی را از طریقِ آدرسِ عمومی‌اش می‌خواند تا همان چیزی را ببیند که بازدیدکننده می‌بیند.
            </x-slot>

            @if ($mediaLinkOk)
                <p class="text-sm text-success-600 dark:text-success-400">
                    <x-filament::icon icon="heroicon-o-check-circle" class="inline h-4 w-4" />
                    کار می‌کند — فایل‌های آپلودشده به‌صورتِ عمومی در دسترس‌اند.
                </p>
            @else
                <p class="mb-3 text-sm text-danger-600 dark:text-danger-400">
                    <x-filament::icon icon="heroicon-o-x-circle" class="inline h-4 w-4" />
                    مشکل — فایلِ آزمایشی از طریقِ آدرسِ عمومی خوانده نشد
                    @if ($mediaLinkStatus) (کدِ {{ $mediaLinkStatus }}) @endif.
                </p>
                <div class="flex flex-wrap items-center gap-2">
                    <x-filament::button wire:click="makeStorageLink" icon="heroicon-o-link" color="primary" wire:loading.attr="disabled">
                        ساختِ لینکِ storage
                    </x-filament::button>
                    <x-filament::button wire:click="checkMediaLink" icon="heroicon-o-arrow-path" color="gray" wire:loading.attr="disabled">
                        بررسیِ دوباره
                    </x-filament::button>
                </div>
            @endif
        </x-filament::section>

        {{-- ۴. بهینه‌سازیِ تصویر (WebP) --}}
        <x-filament::section>
            <x-slot name="heading">بهینه‌سازیِ تصویر (WebP)</x-slot>
            <x-slot name="description">
                تصاویرِ تازه‌ی آپلودشده برای بارگذاریِ سریع‌تر به نسخه‌ی کوچک‌ترِ WebP تبدیل می‌شوند. این نیازمندِ پشتیبانیِ کتابخانه‌ی تصویرِ سرور (PHP GD) از WebP است.
            </x-slot>

            @if ($webpOk)
                <p class="text-sm text-success-600 dark:text-success-400">
                    <x-filament::icon icon="heroicon-o-check-circle" class="inline h-4 w-4" />
                    کار می‌کند — تبدیلِ WebP روی این سرور در دسترس است.
                </p>
            @else
                <p class="text-sm text-danger-600 dark:text-danger-400">
                    <x-filament::icon icon="heroicon-o-x-circle" class="inline h-4 w-4" />
                    در دسترس نیست — کتابخانه‌ی GD روی این سرور از WebP پشتیبانی نمی‌کند.
                </p>
            @endif
        </x-filament::section>

        {{-- ابزارهای بیشتر --}}
        <x-filament::section collapsible collapsed>
            <x-slot name="heading">ابزارهای بیشتر</x-slot>
            <div class="flex flex-wrap items-center gap-4 text-sm">
                <x-filament::link :href="url('panel/manager/maintenance/seo-check?nocache=1')" target="_blank" icon="heroicon-o-shield-check">
                    بررسیِ سئو (seo-check)
                </x-filament::link>
                <x-filament::link :href="url('panel/manager/maintenance/media-backfill')" target="_blank" icon="heroicon-o-photo">
                    Backfillِ رسانه در Media Library
                </x-filament::link>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>

<x-filament-panels::page>
    {{-- راهنمای ساختارِ JSON — همیشه دمِ دست، بازشونده (پیش‌فرض بسته) --}}
    <details style="margin-bottom: 1.25rem; border: 1px solid #e5e7eb; border-radius: 0.7rem; background: #f9fafb; padding: 0.4rem 0.9rem;">
        <summary style="cursor: pointer; font-weight: 700; padding: 0.5rem 0; color: #374151;">
            📄 راهنمای ساختارِ JSON برای ایمپورت (کلیک کنید)
        </summary>
        <div dir="rtl" style="font-size: 0.85rem; line-height: 1.9; color: #374151; padding: 0.5rem 0 0.8rem;">
            <p style="margin: 0.3rem 0;"><b>دو حالت:</b> برای <b>مقاله‌ی جدید</b> کلیدِ <code>id</code> را نگذارید. برای <b>ویرایشِ مقاله‌ی موجود</b> کلیدِ <code>id</code> را بگذارید (آن‌وقت <b>slug قفل</b> می‌شود).</p>

            <p style="margin: 0.6rem 0 0.2rem;"><b>نمونه:</b></p>
            <pre dir="ltr" style="overflow-x: auto; background: #0f172a; color: #e2e8f0; padding: 0.8rem; border-radius: 0.5rem; font-size: 0.78rem; line-height: 1.6;">{
  "id": 139,                         // فقط برای ویرایش؛ برای مقاله‌ی جدید حذف کنید
  "lang": "fa",
  "title": "همه چیز در مورد کاراته",
  "slug": "همه-چیز-در-مورد-کاراته",   // فارسی مجاز و عیناً حفظ می‌شود
  "excerpt": "خلاصه‌ی کوتاه.",
  "body": "&lt;p&gt;متنِ کاملِ HTML با لینک‌های داخلی و عکس‌ها...&lt;/p&gt;",
  "seo_title": "...",
  "meta_description": "...",
  "og_title": "...",
  "og_description": "...",
  "image": "media/library/foo.webp", // تصویرِ شاخص از کتابخانه‌ی رسانه
  "image_alt": "متنِ جایگزینِ تصویر",
  "author_name": "احسان دیبازر",
  "reading_time": 17,
  "tags": ["کاراته", "دفاع شخصی"],
  "faqs": [{ "question": "؟", "answer": "..." }],
  "status": "published"              // draft | published
}</pre>

            <table style="width: 100%; border-collapse: collapse; margin-top: 0.6rem; font-size: 0.8rem;">
                <thead><tr style="text-align: right; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 0.35rem;">کلید</th><th style="padding: 0.35rem;">توضیح</th>
                </tr></thead>
                <tbody>
                    <tr style="border-bottom:1px solid #f3f4f6;"><td style="padding:0.35rem;"><code>title</code> / <code>body</code></td><td style="padding:0.35rem;"><b>الزامی.</b> body عیناً ذخیره می‌شود (لینک‌ها و HTML حفظ).</td></tr>
                    <tr style="border-bottom:1px solid #f3f4f6;"><td style="padding:0.35rem;"><code>slug</code></td><td style="padding:0.35rem;">فارسی عیناً حفظ می‌شود. نگذارید = از عنوان ساخته می‌شود. در ویرایش قفل است.</td></tr>
                    <tr style="border-bottom:1px solid #f3f4f6;"><td style="padding:0.35rem;"><code>image</code></td><td style="padding:0.35rem;">تصویرِ شاخص. مسیرِ نسبی (<code>media/library/x.webp</code>)، یا <code>/storage/…</code>، یا آدرسِ کاملِ همان تصویر از سایت — همه درست تبدیل می‌شوند. (جایگزین: <code>image_path</code>، <code>featured_image</code>)</td></tr>
                    <tr style="border-bottom:1px solid #f3f4f6;"><td style="padding:0.35rem;"><code>image_alt</code></td><td style="padding:0.35rem;">متنِ جایگزینِ تصویر (ALT).</td></tr>
                    <tr style="border-bottom:1px solid #f3f4f6;"><td style="padding:0.35rem;"><code>tags</code></td><td style="padding:0.35rem;">آرایه‌ای از نامِ برچسب‌ها. (فقط اضافه می‌کند.)</td></tr>
                    <tr style="border-bottom:1px solid #f3f4f6;"><td style="padding:0.35rem;"><code>faqs</code></td><td style="padding:0.35rem;">آرایه‌ای از <code>{question, answer}</code>.</td></tr>
                    <tr style="border-bottom:1px solid #f3f4f6;"><td style="padding:0.35rem;">سئو</td><td style="padding:0.35rem;"><code>seo_title</code>, <code>meta_description</code>, <code>og_title</code>, <code>og_description</code>, <code>canonical_url</code>, <code>robots</code>.</td></tr>
                    <tr><td style="padding:0.35rem;">دیگر</td><td style="padding:0.35rem;"><code>excerpt</code>, <code>author_name</code>, <code>reading_time</code>, <code>published_at</code>, <code>translation_of</code>, <code>lang</code>.</td></tr>
                </tbody>
            </table>

            <p style="margin: 0.7rem 0 0.2rem;"><b>🔒 خطِ قرمز:</b> در حالتِ ویرایش، <code>slug</code> (URL)، زبان و مالک هیچ‌وقت عوض نمی‌شوند؛ هر فیلدی که در فایل نباشد دست‌نخورده می‌ماند (چیزی پاک نمی‌شود).</p>
            <p style="margin: 0.3rem 0 0; color: #6b7280;">دسته‌بندی و تغییراتِ ساختاری از فرمِ ویرایشِ مقاله انجام می‌شوند، نه این فایل.</p>
        </div>
    </details>

    <form wire:submit="runImport">
        {{ $this->form }}

        <div style="margin-top: 1.5rem;">
            <x-filament::button type="submit" icon="heroicon-o-sparkles">
                ایمپورت
            </x-filament::button>
        </div>
    </form>

    {{-- پیش‌نمایشِ آپدیتِ مقاله‌ی موجود (چرخه‌ی ویرایشِ AI) --}}
    @if ($pendingUpdate)
        <x-filament::section style="margin-top: 1.5rem;">
            <x-slot name="heading">به‌روزرسانیِ مقاله‌ی موجود</x-slot>
            <x-slot name="description">
                این فایل مقاله‌ی #{{ $pendingUpdate['article_id'] }} — «{{ $pendingUpdate['title'] }}» — را ویرایش می‌کند (درافتِ جدید ساخته نمی‌شود).
            </x-slot>

            {{-- خطِ قرمزِ URL --}}
            <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; border-radius: 0.6rem; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; font-size: 0.85rem;">
                🔒 نشانیِ صفحه (slug) قفل است و تغییر نمی‌کند: <code dir="ltr">{{ $pendingUpdate['slug'] }}</code>
                @if ($pendingUpdate['slug_ignored'])
                    <div style="margin-top: 0.4rem; color: #991b1b;">
                        ⚠️ فایل می‌خواست slug را به <code dir="ltr">{{ $pendingUpdate['incoming_slug'] }}</code> تغییر دهد — <b>نادیده گرفته شد (خطِ قرمز)</b>.
                    </div>
                @endif
            </div>

            {{-- هشدارِ تعارض --}}
            @if ($pendingUpdate['conflict'])
                <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; border-radius: 0.6rem; background: #fffbeb; border: 1px solid #fde68a; color: #92400e; font-size: 0.85rem;">
                    ⚠️ این مقاله بعد از گرفتنِ خروجی در پنل تغییر کرده است. اگر ادامه دهید، ویرایش‌های بعدی بازنویسی می‌شوند. مطمئن شوید نسخه‌ی درست را وارد می‌کنید.
                </div>
            @endif

            {{-- جدولِ تغییرات --}}
            @if (count($pendingUpdate['changes']) > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; font-size: 0.85rem; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: right; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                                <th style="padding: 0.5rem;">فیلد</th>
                                <th style="padding: 0.5rem;">مقدارِ فعلی</th>
                                <th style="padding: 0.5rem;">مقدارِ جدید (از فایل)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingUpdate['changes'] as $field => $vals)
                                @php
                                    // پیش‌نمایش تگ‌ها را برای خوانایی حذف می‌کند، ولی خودِ بادیِ کامل (با همه‌ی
                                    // لینک‌های داخلی و HTML) بی‌کم‌وکاست ذخیره می‌شود. برای فیلدِ body شمارشِ
                                    // کاراکتر و «لینکِ داخلی» را هم نشان می‌دهیم تا کاربر مطمئن شود چیزی گم نشده.
                                    $isBody = $field === 'body';
                                    $limit = $isBody ? 500 : 160;
                                    $oldTxt = strip_tags((string) $vals['old']);
                                    $newTxt = strip_tags((string) $vals['new']);
                                    $oldLinks = substr_count((string) $vals['old'], '<a ');
                                    $newLinks = substr_count((string) $vals['new'], '<a ');
                                @endphp
                                <tr style="border-bottom: 1px solid #f3f4f6; vertical-align: top;">
                                    <td style="padding: 0.5rem; font-weight: 700; white-space: nowrap;">{{ $field }}</td>
                                    <td style="padding: 0.5rem; color: #991b1b;">
                                        {{ \Illuminate\Support\Str::limit($oldTxt, $limit) ?: '—' }}
                                        @if ($isBody)
                                            <div style="color:#6b7280; font-size:0.72rem; margin-top:0.4rem;">{{ mb_strlen($oldTxt) }} کاراکتر · {{ $oldLinks }} لینکِ داخلی</div>
                                        @endif
                                    </td>
                                    <td style="padding: 0.5rem; color: #065f46;">
                                        {{ \Illuminate\Support\Str::limit($newTxt, $limit) }}
                                        @if ($isBody)
                                            <div style="color:#6b7280; font-size:0.72rem; margin-top:0.4rem;">{{ mb_strlen($newTxt) }} کاراکتر · {{ $newLinks }} لینکِ داخلی — کلِ HTML و لینک‌ها کامل ذخیره می‌شوند</div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="font-size: 0.85rem; color: #6b7280;">تغییری در محتوا نیست (فقط تلاش برای تغییرِ slug بود که نادیده گرفته شد).</p>
            @endif

            <div style="margin-top: 1.25rem; display: flex; gap: 0.5rem;">
                <x-filament::button wire:click="confirmUpdate" icon="heroicon-o-check" color="success">
                    تأیید و به‌روزرسانیِ مقاله
                </x-filament::button>
                <x-filament::button wire:click="cancelUpdate" color="gray" icon="heroicon-o-x-mark">
                    انصراف
                </x-filament::button>
            </div>
        </x-filament::section>
    @endif

    {{-- کارتِ نتیجه‌ی آخرین ایمپورتِ موفق --}}
    @if ($importedInfo)
        <x-filament::section class="fi-mt-6" style="margin-top: 1.5rem;">
            <x-slot name="heading">مقاله ایمپورت شد</x-slot>

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <div>
                    <strong>{{ $importedInfo['title'] }}</strong>
                    @if ($importedInfo['published'])
                        <x-filament::badge color="success">منتشر شده</x-filament::badge>
                    @else
                        <x-filament::badge color="gray">پیش‌نویس</x-filament::badge>
                    @endif
                </div>

                <div>
                    <x-filament::link :href="$importedInfo['edit_url']">
                        ویرایش مقاله
                    </x-filament::link>
                </div>
            </div>
        </x-filament::section>
    @endif

    {{-- تاریخچه‌ی سبکِ ایمپورت --}}
    <x-filament::section style="margin-top: 1.5rem;">
        <x-slot name="heading">واردات اخیر</x-slot>

        @if ($this->recentLogs->isEmpty())
            <p style="color: var(--gray-500);">هنوز ایمپورتی ثبت نشده است.</p>
        @else
            <div style="overflow-x: auto;">
                <table class="fi-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: right;">
                            <th style="padding: 0.5rem;">عنوان</th>
                            <th style="padding: 0.5rem;">زبان</th>
                            <th style="padding: 0.5rem;">وضعیت</th>
                            <th style="padding: 0.5rem;">کاربر</th>
                            <th style="padding: 0.5rem;">تاریخ</th>
                            <th style="padding: 0.5rem;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->recentLogs as $log)
                            <tr style="border-top: 1px solid var(--gray-200);">
                                <td style="padding: 0.5rem;">{{ $log->article_title ?? '—' }}</td>
                                <td style="padding: 0.5rem;">{{ strtoupper($log->locale ?? '') }}</td>
                                <td style="padding: 0.5rem;">
                                    @if ($log->isRolledBack())
                                        <x-filament::badge color="warning">بازگردانده‌شده</x-filament::badge>
                                    @elseif ($log->status === 'imported')
                                        <x-filament::badge color="success">موفق</x-filament::badge>
                                    @else
                                        <x-filament::badge color="danger">ناموفق</x-filament::badge>
                                    @endif
                                </td>
                                <td style="padding: 0.5rem;">{{ $log->user?->name ?? '—' }}</td>
                                <td style="padding: 0.5rem;">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                                <td style="padding: 0.5rem;">
                                    @if ($log->canRollBack())
                                        <x-filament::button
                                            size="xs"
                                            color="danger"
                                            wire:click="rollbackLog({{ $log->id }})"
                                            wire:confirm="این ایمپورت بازگردانده شود؟ مقاله‌ی ساخته‌شده حذف می‌شود.">
                                            بازگردانی
                                        </x-filament::button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>

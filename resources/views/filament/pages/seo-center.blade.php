<x-filament-panels::page>
    <style>
        .seo-center{display:grid;grid-template-columns:260px 1fr;gap:1.5rem;align-items:start;direction:rtl}
        @media(max-width:900px){.seo-center{grid-template-columns:1fr}}
        .seo-summary{border:1px solid rgb(229 231 235);border-radius:.75rem;background:#fff;padding:1rem;margin-bottom:1rem}
        .dark .seo-summary{background:#18181b;border-color:#3f3f46}
        .seo-summary .count{font-size:1.6rem;font-weight:700;color:#374151}
        .dark .seo-summary .count{color:#e4e4e7}
        .seo-summary .label{font-size:.8rem;color:#6b7280}
        .seo-summary-actions{display:flex;gap:.5rem;margin-top:.75rem;flex-wrap:wrap}
        .seo-categories{border:1px solid rgb(229 231 235);border-radius:.75rem;background:#fff;padding:.5rem;display:flex;flex-direction:column;gap:.15rem}
        .dark .seo-categories{background:#18181b;border-color:#3f3f46}
        .seo-cat-btn{display:flex;align-items:center;justify-content:space-between;gap:.5rem;padding:.55rem .65rem;border-radius:.5rem;font-size:.82rem;color:#374151;text-decoration:none;cursor:pointer;background:none;border:none;text-align:right;width:100%}
        .dark .seo-cat-btn{color:#d4d4d8}
        .seo-cat-btn:hover{background:#f3f4f6}
        .dark .seo-cat-btn:hover{background:#27272a}
        .seo-cat-btn.active{background:#fef3c7;font-weight:600}
        .dark .seo-cat-btn.active{background:#3f3a1e}
        .seo-cat-badge{min-width:1.5rem;text-align:center;border-radius:9999px;padding:.05rem .4rem;font-size:.72rem;font-weight:700;background:#f3f4f6;color:#6b7280}
        .seo-cat-badge.has-issues{background:#fee2e2;color:#b91c1c}
        .seo-cat-badge.clean{background:#dcfce7;color:#166534}
        .seo-toolbar{display:flex;flex-wrap:wrap;gap:.6rem;align-items:center;margin-bottom:.85rem}
        .seo-toolbar input[type=search],.seo-toolbar select{border:1px solid rgb(209 213 219);border-radius:.5rem;padding:.4rem .6rem;font-size:.85rem;background:#fff}
        .dark .seo-toolbar input[type=search],.dark .seo-toolbar select{background:#27272a;border-color:#3f3f46;color:#e4e4e7}
        .seo-toolbar input[type=search]{min-width:220px}
        .seo-category-note{background:#eff6ff;border:1px solid #bfdbfe;border-radius:.5rem;padding:.6rem .8rem;font-size:.8rem;color:#1e40af;margin-bottom:.85rem}
        .seo-category-note.warn{background:#fffbeb;border-color:#fde68a;color:#92400e}
        table.seo-findings{width:100%;border-collapse:collapse;font-size:.83rem;background:#fff;border:1px solid rgb(229 231 235);border-radius:.75rem;overflow:hidden}
        .dark table.seo-findings{background:#18181b;border-color:#3f3f46}
        table.seo-findings th{text-align:right;background:#f9fafb;padding:.6rem .75rem;font-size:.72rem;color:#6b7280;border-bottom:1px solid rgb(229 231 235)}
        .dark table.seo-findings th{background:#27272a;color:#a1a1aa;border-color:#3f3f46}
        table.seo-findings td{padding:.6rem .75rem;border-bottom:1px solid rgb(243 244 246);vertical-align:top;color:#374151}
        .dark table.seo-findings td{border-color:#27272a;color:#d4d4d8}
        table.seo-findings tr:last-child td{border-bottom:none}
        table.seo-findings .badge{display:inline-block;padding:.1rem .5rem;border-radius:9999px;font-size:.7rem;background:#f3f4f6;color:#4b5563}
        table.seo-findings .detail{color:#4b5563;white-space:pre-line}
        .dark table.seo-findings .detail{color:#a1a1aa}
        .seo-empty{padding:2.5rem 0;text-align:center;color:#9ca3af;font-size:.85rem}
    </style>

    <div class="seo-center">
        <div>
            <div class="seo-summary">
                <div class="count">{{ $this->totalIssues }}</div>
                <div class="label">مورد یافت شد</div>
                <div class="seo-summary-actions">
                    <x-filament::button size="sm" color="gray" wire:click="runAudit" icon="heroicon-o-arrow-path">
                        اجرای ممیزی
                    </x-filament::button>
                    <x-filament::button size="sm" color="gray" wire:click="exportFullReportCsv" icon="heroicon-o-arrow-down-tray">
                        خروجیِ کامل
                    </x-filament::button>
                </div>
            </div>

            <div class="seo-categories">
                @foreach(\App\Filament\Pages\SeoCenter::CATEGORIES as $key => $label)
                    @php($count = $this->categoryCounts[$key] ?? 0)
                    <button type="button" class="seo-cat-btn {{ $activeCategory === $key ? 'active' : '' }}" wire:click="setCategory('{{ $key }}')">
                        <span>{{ $label }}</span>
                        <span class="seo-cat-badge {{ $count > 0 ? 'has-issues' : 'clean' }}">{{ $count }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <div>
            @if($activeCategory === 'missing_canonicals')
                <div class="seo-category-note">
                    هر صفحه به‌صورتِ خودکار از layout (master.blade) یک canonical می‌گیرد، پس این دسته انتظاراً ۰ می‌ماند — اینجا هست تا اگر روزی آن fallback حذف شد فوراً مشخص شود.
                </div>
            @endif

            @if(in_array($activeCategory, ['missing_alt','untranslated_alt','missing_schema'], true))
                <div class="seo-category-note">
                    این بررسی سطحِ قالب است و با ویرایشِ یک رکورد رفع نمی‌شود — در نسخه‌ی بعد فعال می‌شود.
                </div>
            @endif

            @if($activeCategory === 'broken_external_links')
                <div class="seo-category-note {{ $hasScannedExternalLinks ? '' : 'warn' }}">
                    @if($hasScannedExternalLinks)
                        آخرین اسکن همه‌ی لینک‌های خارجیِ بدنه‌ی مقاله/صفحه را بررسی کرد.
                    @else
                        لینک‌های خارجی خودکار بررسی نمی‌شوند (تماسِ واقعیِ HTTP به سایت‌های دیگر) — «اسکنِ لینک‌های خارجی» را بزنید.
                    @endif
                    <div style="margin-top:.5rem">
                        <x-filament::button size="sm" wire:click="scanExternalLinks" icon="heroicon-o-globe-alt">
                            اسکنِ لینک‌های خارجی
                        </x-filament::button>
                    </div>
                </div>
            @endif

            <div class="seo-toolbar">
                <input type="search" wire:model.live.debounce.400ms="search" placeholder="جست‌وجو در این فهرست…">

                <select wire:model.live="localeFilter">
                    <option value="all">همه‌ی زبان‌ها</option>
                    <option value="fa">فارسی</option>
                    <option value="en">English</option>
                </select>

                @if(count($this->availableTypes) > 1)
                    <select wire:model.live="typeFilter">
                        <option value="all">همه‌ی نوع‌ها</option>
                        @foreach($this->availableTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                @endif

                <x-filament::button size="sm" color="gray" wire:click="exportCategoryCsv" icon="heroicon-o-arrow-down-tray">
                    خروجیِ این نما (CSV)
                </x-filament::button>
            </div>

            <table class="seo-findings">
                <thead>
                    <tr>
                        <th style="width:90px">نوع</th>
                        <th style="width:60px">زبان</th>
                        <th>مورد</th>
                        <th>مشکل</th>
                        <th style="width:90px">اصلاح</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->filteredFindings as $finding)
                        <tr wire:key="finding-{{ $activeCategory }}-{{ $loop->index }}">
                            <td><span class="badge">{{ $finding['type'] === 'article' ? 'مقاله' : ($finding['type'] === 'page' ? 'صفحه' : $finding['type']) }}</span></td>
                            <td>{{ ($finding['locale'] ?? '') ? strtoupper($finding['locale']) : '—' }}</td>
                            <td>{{ $finding['title'] }}</td>
                            <td class="detail">{{ $finding['detail'] }}</td>
                            <td>
                                @if(!empty($finding['edit_url']))
                                    <x-filament::button size="xs" color="gray" tag="a" :href="$finding['edit_url']" target="_blank">
                                        ویرایش
                                    </x-filament::button>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="seo-empty">
                                    @if($activeCategory === 'broken_external_links' && ! $hasScannedExternalLinks)
                                        هنوز اسکن نشده — «اسکنِ لینک‌های خارجی» را بزنید.
                                    @else
                                        در این دسته موردی یافت نشد.
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>

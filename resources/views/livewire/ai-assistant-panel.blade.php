<div>
    <style>
        .ai-ca-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:.5rem}
        .ai-ca-header .title{font-size:.9rem;color:#6b7280}
        .ai-ca-header .title strong{color:#111827}

        .ai-ca-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem}
        .ai-ca-card{border:1px solid rgb(229 231 235);border-radius:.75rem;background:#fff;padding:1rem}
        .ai-ca-card h3{font-size:.85rem;font-weight:600;color:#111827;margin:0 0 .4rem}

        .ai-ca-current{font-size:.8rem;color:#4b5563;background:#f9fafb;border:1px solid rgb(243 244 246);border-radius:.5rem;padding:.5rem .6rem;margin-bottom:.6rem;max-height:6rem;overflow-y:auto;white-space:pre-line}
        .ai-ca-current.empty{color:#9ca3af;font-style:italic}

        .ai-ca-modes{display:flex;flex-wrap:wrap;gap:.35rem;margin-bottom:.6rem}
        .ai-ca-mode-btn{font-size:.72rem;padding:.3rem .55rem;border-radius:9999px;border:1px solid rgb(209 213 219);background:#fff;color:#374151;cursor:pointer}
        .ai-ca-mode-btn:hover{background:#f3f4f6}

        .ai-ca-status{font-size:.75rem;color:#6b7280;margin-bottom:.5rem;display:flex;align-items:center;gap:.4rem}
        .ai-ca-status.processing{color:#b45309}
        .ai-ca-status.failed{color:#b91c1c}
        .ai-ca-cancel{color:#b91c1c;background:none;border:none;cursor:pointer;font-size:.7rem;text-decoration:underline;padding:0}

        .ai-ca-preview{background:#eff6ff;border:1px solid #bfdbfe;border-radius:.5rem;padding:.6rem .7rem;font-size:.8rem;color:#1e3a8a;margin-bottom:.5rem;white-space:pre-line}
        .ai-ca-preview ul{margin:.25rem 0 0 1.1rem;padding:0}
        .ai-ca-preview .qa{margin-bottom:.4rem}
        .ai-ca-preview .qa strong{display:block}

        .ai-ca-actions{display:flex;gap:.4rem;margin-bottom:.6rem}

        .ai-ca-history{font-size:.72rem;color:#9ca3af}
        .ai-ca-history-item{display:flex;justify-content:space-between;align-items:center;padding:.2rem 0;border-top:1px solid rgb(243 244 246)}

        .ai-ca-knowledge{font-size:.7rem;color:#6b7280;background:#f9fafb;border:1px solid rgb(243 244 246);border-radius:.4rem;padding:.3rem .5rem;margin-bottom:.5rem}

        .ai-ca-knowledge-chunks{font-size:.7rem;color:#6b7280;background:#f9fafb;border:1px solid rgb(243 244 246);border-radius:.4rem;padding:.3rem .5rem;margin-bottom:.5rem}
        .ai-ca-knowledge-chunks summary{cursor:pointer;font-weight:500}
        .ai-ca-chunk-list{list-style:none;margin:.4rem 0 0;padding:0;display:flex;flex-direction:column;gap:.4rem}
        .ai-ca-chunk-list li{border-top:1px solid rgb(243 244 246);padding-top:.35rem}
        .ai-ca-chunk-meta{display:flex;align-items:center;gap:.35rem;margin-bottom:.15rem;color:#374151}
        .ai-ca-chunk-badge{font-size:.62rem;font-weight:600;padding:.05rem .35rem;border-radius:999px;background:#fef3c7;color:#92400e}
        .ai-ca-chunk-score{margin-left:auto;font-variant-numeric:tabular-nums;color:#059669;font-weight:600}
        .ai-ca-chunk-text{color:#6b7280;line-height:1.4}

        .ai-ca-tabs{display:flex;gap:.4rem;margin-bottom:1rem;border-bottom:1px solid rgb(229 231 235)}
        .ai-ca-tab-btn{padding:.5rem .9rem;font-size:.82rem;color:#6b7280;background:none;border:none;border-bottom:2px solid transparent;cursor:pointer}
        .ai-ca-tab-btn.active{color:#111827;font-weight:600;border-bottom-color:#d9bb75}

        .ai-ca-findings{border:1px solid rgb(229 231 235);border-radius:.75rem;background:#fff;overflow:hidden;margin-bottom:1rem}
        .ai-ca-finding{display:flex;gap:.6rem;align-items:flex-start;padding:.6rem .8rem;border-bottom:1px solid rgb(243 244 246);font-size:.82rem}
        .ai-ca-finding:last-child{border-bottom:none}
        .ai-ca-finding .badge{flex-shrink:0;padding:.05rem .5rem;border-radius:9999px;font-size:.68rem;font-weight:600;text-transform:uppercase}
        .ai-ca-finding .badge.warning{background:#fee2e2;color:#b91c1c}
        .ai-ca-finding .badge.notice{background:#fef3c7;color:#92400e}
        .ai-ca-findings-empty{padding:1.5rem;text-align:center;color:#9ca3af;font-size:.85rem}

        .ai-ca-diff{font-size:.8rem;line-height:1.5;background:#fff;border:1px solid rgb(229 231 235);border-radius:.5rem;padding:.5rem .6rem;margin-bottom:.5rem;max-height:8rem;overflow-y:auto}
        .ai-ca-diff del{background:#fee2e2;color:#991b1b;text-decoration:line-through}
        .ai-ca-diff ins{background:#dcfce7;color:#166534;text-decoration:none}

        .ai-ca-score{border:1px solid rgb(229 231 235);border-radius:.75rem;background:#fff;padding:1rem;margin-bottom:1rem}
        .ai-ca-score-head{display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem}
        .ai-ca-score-overall{font-size:1.6rem;font-weight:700;color:#111827;flex-shrink:0}
        .ai-ca-score-overall .max{font-size:.9rem;font-weight:400;color:#9ca3af}
        .ai-ca-score-label{font-size:.78rem;color:#6b7280}
        .ai-ca-score-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.5rem}
        .ai-ca-score-cat{border:1px solid rgb(243 244 246);border-radius:.5rem;padding:.5rem .6rem}
        .ai-ca-score-cat .name{font-size:.72rem;color:#6b7280;margin-bottom:.15rem}
        .ai-ca-score-cat .num{font-size:1.1rem;font-weight:600}
        .ai-ca-score-cat .num.good{color:#166534}
        .ai-ca-score-cat .num.mid{color:#92400e}
        .ai-ca-score-cat .num.bad{color:#b91c1c}
        .ai-ca-score-cat .issues{margin:.3rem 0 0 1rem;padding:0;font-size:.68rem;color:#9ca3af}

        .ai-ca-quick-actions{display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:1rem}
        .ai-ca-quick-btn{font-size:.75rem;padding:.35rem .65rem;border-radius:.5rem;border:1px solid rgb(209 213 219);background:#f9fafb;color:#374151;cursor:pointer}
        .ai-ca-quick-btn:hover{background:#f3f4f6}

        .ai-ca-chat{border:1px solid rgb(229 231 235);border-radius:.75rem;background:#fff;padding:.75rem;margin-bottom:1rem}
        .ai-ca-chat-thread{max-height:14rem;overflow-y:auto;display:flex;flex-direction:column;gap:.5rem;margin-bottom:.6rem}
        .ai-ca-chat-empty{font-size:.78rem;color:#9ca3af;font-style:italic;padding:.25rem 0}
        .ai-ca-chat-msg{display:flex}
        .ai-ca-chat-msg.user{justify-content:flex-end}
        .ai-ca-chat-msg.assistant{justify-content:flex-start}
        .ai-ca-chat-msg .bubble{max-width:85%;font-size:.8rem;padding:.45rem .65rem;border-radius:.65rem;white-space:pre-line}
        .ai-ca-chat-msg.user .bubble{background:#d9bb75;color:#111827}
        .ai-ca-chat-msg.assistant .bubble{background:#f3f4f6;color:#111827}
        .ai-ca-chat-msg .bubble.typing{font-style:italic;color:#6b7280}
        .ai-ca-chat-form{display:flex;gap:.4rem}
        .ai-ca-chat-input{flex:1;font-size:.8rem;padding:.4rem .6rem;border-radius:.5rem;border:1px solid rgb(209 213 219)}

        /* Filament toggles dark mode via a `dark` class on <html>, not prefers-color-scheme — match that mechanism */
        :root.dark .ai-ca-header .title{color:#9ca3af}
        :root.dark .ai-ca-header .title strong{color:#f9fafb}
        :root.dark .ai-ca-card,
        :root.dark .ai-ca-findings,
        :root.dark .ai-ca-score,
        :root.dark .ai-ca-diff{background:#1f2937;border-color:#374151}
        :root.dark .ai-ca-card h3{color:#f9fafb}
        :root.dark .ai-ca-current{background:#111827;border-color:#374151;color:#d1d5db}
        :root.dark .ai-ca-mode-btn,
        :root.dark .ai-ca-quick-btn{background:#111827;border-color:#374151;color:#d1d5db}
        :root.dark .ai-ca-mode-btn:hover,
        :root.dark .ai-ca-quick-btn:hover{background:#1f2937}
        :root.dark .ai-ca-preview{background:#1e3a5f;border-color:#1e40af;color:#dbeafe}
        :root.dark .ai-ca-diff del{background:#450a0a;color:#fca5a5}
        :root.dark .ai-ca-diff ins{background:#14532d;color:#86efac}
        :root.dark .ai-ca-tabs{border-color:#374151}
        :root.dark .ai-ca-tab-btn{color:#9ca3af}
        :root.dark .ai-ca-tab-btn.active{color:#f9fafb}
        :root.dark .ai-ca-finding{border-color:#374151}
        :root.dark .ai-ca-score-overall{color:#f9fafb}
        :root.dark .ai-ca-score-cat{border-color:#374151}
        :root.dark .ai-ca-score-cat .name{color:#9ca3af}
        :root.dark .ai-ca-history-item{border-color:#374151}
        :root.dark .ai-ca-knowledge{background:#111827;border-color:#374151;color:#9ca3af}
        :root.dark .ai-ca-knowledge-chunks{background:#111827;border-color:#374151;color:#9ca3af}
        :root.dark .ai-ca-chunk-list li{border-color:#374151}
        :root.dark .ai-ca-chunk-meta{color:#d1d5db}
        :root.dark .ai-ca-chunk-badge{background:#78350f;color:#fde68a}
        :root.dark .ai-ca-chunk-score{color:#34d399}
        :root.dark .ai-ca-chat{background:#1f2937;border-color:#374151}
        :root.dark .ai-ca-chat-msg.assistant .bubble{background:#111827;color:#e5e7eb}
        :root.dark .ai-ca-chat-msg.user .bubble{color:#111827}
        :root.dark .ai-ca-chat-input{background:#111827;border-color:#374151;color:#e5e7eb}
    </style>

    <div @if ($this->isPolling || $this->isChatPending) wire:poll.3s="$refresh" @endif>
        <div class="ai-ca-header">
            <div class="title">دستیار هوش مصنوعی برای <strong>{{ $record->title }}</strong> ({{ strtoupper($record->locale) }})</div>
            @if ($standalone)
                <x-filament::button size="sm" color="gray" tag="a" :href="$this->editUrl()" icon="heroicon-o-arrow-left">
                    بازگشت به ویرایش
                </x-filament::button>
            @endif
        </div>

        {{-- موقتاً: چت هوش مصنوعی هنوز منتقل نشده — نخِ گفتگو خالی می‌ماند و ارسال فقط پیام «گروه بعد» نشان می‌دهد --}}
        <div class="ai-ca-chat">
            <div class="ai-ca-chat-thread">
                @forelse ($this->chatMessages as $chatMessage)
                    <div class="ai-ca-chat-msg {{ $chatMessage->role }}">
                        <div class="bubble">{{ $chatMessage->message }}</div>
                    </div>
                @empty
                    <div class="ai-ca-chat-empty">
                        هر سؤالی درباره‌ی این {{ $recordType === 'Article' ? 'مقاله' : 'صفحه' }} بپرسید — مثلاً «۵ پرسش متداول بساز»، «مقدمه را بهتر کن»، «کوتاه‌ترش کن» یا «به انگلیسی ترجمه کن».
                    </div>
                @endforelse

                @if ($this->isChatPending)
                    <div class="ai-ca-chat-msg assistant">
                        <div class="bubble typing">در حال فکر کردن…</div>
                    </div>
                @endif
            </div>

            <form wire:submit.prevent="sendChatMessage" class="ai-ca-chat-form">
                <input type="text" wire:model="chatInput" placeholder="از دستیار هوش مصنوعی بپرسید…" class="ai-ca-chat-input" @disabled($this->isChatPending)>
                <x-filament::button type="submit" size="sm" :disabled="$this->isChatPending" wire:loading.attr="disabled">
                    ارسال
                </x-filament::button>
            </form>
        </div>

        <div style="margin-bottom:.75rem">
            <x-filament::button wire:click="optimizeEntireArticle" wire:loading.attr="disabled" wire:confirm="برای هر فیلد قابل‌بهینه‌سازیِ این {{ $recordType === 'Article' ? 'مقاله' : 'صفحه' }} پیشنهاد هوش مصنوعی صف شود؟ هیچ‌چیز خودکار اعمال نمی‌شود — هر مورد را خودتان بررسی و تأیید می‌کنید.">
                ✨ بهینه‌سازی کل مقاله
            </x-filament::button>
        </div>

        <div class="ai-ca-quick-actions">
            <button type="button" class="ai-ca-quick-btn" wire:click="optimizeEntireArticle" wire:loading.attr="disabled" wire:confirm="برای هر فیلد قابل‌بهینه‌سازیِ این {{ $recordType === 'Article' ? 'مقاله' : 'صفحه' }} پیشنهاد هوش مصنوعی صف شود؟">تولید همه‌چیز</button>
            <button type="button" class="ai-ca-quick-btn" wire:click="quickSeoOnly" wire:loading.attr="disabled">فقط سئو</button>
            @if ($recordType === 'Article')
                <button type="button" class="ai-ca-quick-btn" wire:click="quickFaqOnly" wire:loading.attr="disabled">فقط پرسش‌های متداول</button>
            @endif
            <button type="button" class="ai-ca-quick-btn" wire:click="quickBodyAction('rewrite')" wire:loading.attr="disabled">بازنویسی</button>
            <button type="button" class="ai-ca-quick-btn" wire:click="quickBodyAction('improve')" wire:loading.attr="disabled">بهبود</button>
            <button type="button" class="ai-ca-quick-btn" wire:click="quickBodyAction('expand')" wire:loading.attr="disabled">گسترش</button>
            <button type="button" class="ai-ca-quick-btn" wire:click="quickBodyAction('simplify')" wire:loading.attr="disabled">ساده‌سازی</button>
            <button type="button" class="ai-ca-quick-btn" wire:click="setTab('review')">بازبینی</button>
        </div>

        @if ($this->isPolling)
            <div class="ai-ca-status processing" style="margin-bottom:1rem">
                در حال تولید{{ $this->generationProgress ? ' — '.$this->generationProgress : '' }}… این صفحه به‌صورت خودکار تازه می‌شود.
            </div>
        @endif

        @php($scoreCard = $this->scoreCard)
        <div class="ai-ca-score">
            <div class="ai-ca-score-head">
                <div class="ai-ca-score-overall">{{ $scoreCard['overall'] }}<span class="max">/100</span></div>
                <div class="ai-ca-score-label">گزارش سلامت هوش مصنوعی — امتیاز کلی میانگین شش دسته‌ی زیر است که هرکدام نشان می‌دهند چه چیزی کم است.</div>
            </div>
            <div class="ai-ca-score-grid">
                @foreach ($scoreCard['categories'] as $category)
                    @php($tier = $category['score'] >= 80 ? 'good' : ($category['score'] >= 50 ? 'mid' : 'bad'))
                    <div class="ai-ca-score-cat">
                        <div class="name">{{ $category['label'] }}</div>
                        <div class="num {{ $tier }}">{{ $category['score'] }}</div>
                        @if ($category['issues'])
                            <ul class="issues">
                                @foreach ($category['issues'] as $issue)
                                    <li>{{ $issue }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="ai-ca-tabs">
            <button type="button" class="ai-ca-tab-btn {{ $activeTab === 'generate' ? 'active' : '' }}" wire:click="setTab('generate')">تولید</button>
            <button type="button" class="ai-ca-tab-btn {{ $activeTab === 'review' ? 'active' : '' }}" wire:click="setTab('review')">بازبینی محتوا</button>
            <button type="button" class="ai-ca-tab-btn {{ $activeTab === 'history' ? 'active' : '' }}" wire:click="setTab('history')">تاریخچه</button>
        </div>

        @if ($activeTab === 'history')
            <div class="ai-ca-findings">
                @forelse ($this->history as $past)
                    <div class="ai-ca-finding" style="align-items:center">
                        <span class="badge {{ $past->status === 'failed' ? 'warning' : 'notice' }}">{{ $past->status }}</span>
                        <span style="flex:1">
                            {{ \App\Services\AiAssistant\ActionRegistry::exists($past->field) ? \App\Services\AiAssistant\ActionRegistry::for($past->field)['label'] : ucfirst($past->field) }}
                            · {{ ucfirst($past->mode) }} · {{ $past->created_at->diffForHumans() }}
                            @if ($past->applied_at) — اعمال‌شده {{ $past->applied_at->diffForHumans() }} @endif
                            @if ($past->restored_at) — بازگردانده‌شده {{ $past->restored_at->diffForHumans() }} @endif
                            @if (! empty($past->retrieved_chunks))
                                <br><span style="color:#6b7280">📚 {{ collect($past->retrieved_chunks)->pluck('source')->unique()->implode('، ') }} ({{ count($past->retrieved_chunks) }} تکه)</span>
                            @endif
                        </span>
                        @php($isAppliable = \App\Services\AiAssistant\ActionRegistry::exists($past->field) ? (\App\Services\AiAssistant\ActionRegistry::for($past->field)['appliable'] ?? true) : false)
                        @if ($past->isCancellable())
                            <button type="button" wire:click="cancelGeneration({{ $past->id }})" wire:confirm="این تولید لغو شود؟" style="color:#b91c1c;background:none;border:none;cursor:pointer;font-size:.72rem">لغو</button>
                        @elseif ($past->canRestore())
                            <button type="button" wire:click="restoreGeneration({{ $past->id }})" wire:confirm="مقدار پیش از اجرای این تولید بازگردانده شود؟" style="color:#2563eb;background:none;border:none;cursor:pointer;font-size:.72rem">بازگردانی</button>
                        @elseif ($past->field === 'internal_links' && $past->canApply() && ! $past->applied_at)
                            <button type="button" wire:click="applyInternalLinkSuggestions({{ $past->id }})" wire:confirm="این‌ها به‌عنوان پیشنهادهای در انتظار در مرکز لینک‌سازی داخلی افزوده شوند؟" style="color:#2563eb;background:none;border:none;cursor:pointer;font-size:.72rem">افزودن پیشنهادها</button>
                        @elseif ($isAppliable && $past->canApply() && ! $past->applied_at)
                            <button type="button" wire:click="applyGeneration({{ $past->id }})" wire:confirm="مقدار فعلی با این پیشنهاد جایگزین شود؟" style="color:#2563eb;background:none;border:none;cursor:pointer;font-size:.72rem">اعمال</button>
                        @endif
                    </div>
                @empty
                    <div class="ai-ca-findings-empty">هنوز هیچ تولید هوش مصنوعی برای این {{ $recordType === 'Article' ? 'مقاله' : 'صفحه' }} وجود ندارد.</div>
                @endforelse
            </div>
        @elseif ($activeTab === 'review')
            <div class="ai-ca-findings">
                @forelse ($this->reviewFindings as $finding)
                    <div class="ai-ca-finding">
                        <span class="badge {{ $finding['severity'] }}">{{ $finding['severity'] }}</span>
                        <span>{{ $finding['message'] }}</span>
                    </div>
                @empty
                    <div class="ai-ca-findings-empty">مشکلی یافت نشد — این محتوا خوب به نظر می‌رسد.</div>
                @endforelse
            </div>

            <div class="ai-ca-card" style="max-width:600px">
                <h3>خلاصه‌ی هوش مصنوعی</h3>
                <x-filament::button size="xs" color="gray" wire:click="generateReviewSummary" wire:loading.attr="disabled">
                    تولید خلاصه
                </x-filament::button>

                @if ($this->reviewSummary)
                    @if (in_array($this->reviewSummary->status, ['queued', 'processing']))
                        <div class="ai-ca-status processing" style="margin-top:.5rem">
                            {{ ucfirst($this->reviewSummary->status) }}…
                            <button type="button" class="ai-ca-cancel" wire:click="cancelGeneration({{ $this->reviewSummary->id }})" wire:confirm="این تولید لغو شود؟">لغو</button>
                        </div>
                    @elseif ($this->reviewSummary->status === 'failed')
                        <div class="ai-ca-status failed" style="margin-top:.5rem">ناموفق: {{ $this->reviewSummary->error }}</div>
                    @elseif ($this->reviewSummary->status === 'completed')
                        <div class="ai-ca-preview" style="margin-top:.5rem">{{ $this->reviewSummary->result }}</div>
                    @endif
                @endif
            </div>
        @else
        <div class="ai-ca-grid">
            @foreach ($this->fields as $field)
                <div class="ai-ca-card">
                    <h3>{{ $field['label'] }}</h3>

                    <div class="ai-ca-current {{ blank($field['current_value']) ? 'empty' : '' }}">
                        @if (blank($field['current_value']))
                            هنوز تنظیم نشده
                        @elseif (is_array($field['current_value']))
                            {{ collect($field['current_value'])->map(fn ($v) => is_array($v) ? ($v['question'] ?? '') : $v)->implode(' · ') }}
                        @else
                            {{ \Illuminate\Support\Str::limit(strip_tags($field['current_value']), 200) }}
                        @endif
                    </div>

                    <div class="ai-ca-modes">
                        @foreach ($field['modes'] as $mode)
                            <button type="button" class="ai-ca-mode-btn" wire:click="generateField('{{ $field['key'] }}', '{{ $mode }}')" wire:loading.attr="disabled">
                                {{ ucfirst($mode) }}
                            </button>
                        @endforeach
                    </div>

                    @if ($field['latest'])
                        @php($latest = $field['latest'])

                        @if (in_array($latest->status, ['queued', 'processing']))
                            <div class="ai-ca-status processing">
                                {{ ucfirst($latest->status) }}…
                                <button type="button" class="ai-ca-cancel" wire:click="cancelGeneration({{ $latest->id }})" wire:confirm="این تولید لغو شود؟">لغو</button>
                            </div>
                        @elseif ($latest->status === 'failed')
                            <div class="ai-ca-status failed">ناموفق: {{ $latest->error }}</div>
                        @elseif ($latest->status === 'completed')
                            @php($diff = $latest->applied_at ? null : $this->diffFor($field['current_value'], $latest->result))

                            @if ($diff)
                                <div class="ai-ca-diff">
                                    @foreach ($diff as $op)
                                        @if ($op['type'] === 'del')
                                            <del>{{ $op['text'] }}</del>
                                        @elseif ($op['type'] === 'add')
                                            <ins>{{ $op['text'] }}</ins>
                                        @else
                                            {{ $op['text'] }}
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="ai-ca-preview">
                                    @if (is_array($latest->result) && isset($latest->result[0]['question']))
                                        @foreach ($latest->result as $qa)
                                            <div class="qa"><strong>{{ $qa['question'] }}</strong>{{ $qa['answer'] }}</div>
                                        @endforeach
                                    @elseif (is_array($latest->result))
                                        <ul>
                                            @foreach ($latest->result as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ $latest->result }}
                                    @endif
                                </div>
                            @endif

                            @if (! empty($latest->retrieved_chunks))
                                <details class="ai-ca-knowledge-chunks">
                                    <summary>📚 دانش استفاده‌شده: {{ collect($latest->retrieved_chunks)->pluck('source')->unique()->implode('، ') }} ({{ count($latest->retrieved_chunks) }} تکه)</summary>
                                    <ul class="ai-ca-chunk-list">
                                        @foreach ($latest->retrieved_chunks as $chunk)
                                            <li>
                                                <div class="ai-ca-chunk-meta">
                                                    <strong>{{ $chunk['source'] ?? $chunk['entry_title'] ?? 'منبع نامشخص' }}</strong>
                                                    @if ($chunk['pinned'] ?? false)
                                                        <span class="ai-ca-chunk-badge">سنجاق‌شده</span>
                                                    @endif
                                                    <span class="ai-ca-chunk-score">{{ number_format((($chunk['score'] ?? 0) * 100), 0) }}٪ تطابق</span>
                                                </div>
                                                <div class="ai-ca-chunk-text">{{ \Illuminate\Support\Str::limit($chunk['text'] ?? '', 220) }}</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </details>
                            @endif

                            @if ($latest->applied_at)
                                <div class="ai-ca-status">اعمال‌شده {{ $latest->applied_at->diffForHumans() }}</div>
                            @else
                                <div class="ai-ca-actions">
                                    <x-filament::button size="xs" wire:click="applyGeneration({{ $latest->id }})" wire:confirm="مقدار فعلی با این پیشنهاد جایگزین شود؟">
                                        اعمال
                                    </x-filament::button>
                                </div>
                            @endif
                        @endif
                    @endif

                    @if ($field['history']->count() > 1)
                        <div class="ai-ca-history">
                            @foreach ($field['history']->skip(1)->take(4) as $past)
                                <div class="ai-ca-history-item">
                                    <span>{{ ucfirst($past->mode) }} · {{ $past->created_at->diffForHumans() }}</span>
                                    @if ($past->canRestore())
                                        <button type="button" wire:click="restoreGeneration({{ $past->id }})" wire:confirm="مقدار پیش از اجرای این تولید بازگردانده شود؟" style="color:#2563eb;background:none;border:none;cursor:pointer;font-size:.72rem">
                                            بازگردانی
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="ai-ca-grid" style="margin-top:1rem">
            @if (isset($this->suggestionFields['internal_links']))
                @php($internalLinksField = $this->suggestionFields['internal_links'])
                <div class="ai-ca-card">
                    <h3>پیشنهادهای لینک داخلی</h3>
                    <x-filament::button size="xs" color="gray" wire:click="generateField('internal_links', 'generate')" wire:loading.attr="disabled">
                        تولید
                    </x-filament::button>

                    @if ($internalLinksField['latest'])
                        @php($latest = $internalLinksField['latest'])
                        @if (in_array($latest->status, ['queued', 'processing']))
                            <div class="ai-ca-status processing">
                                {{ ucfirst($latest->status) }}…
                                <button type="button" class="ai-ca-cancel" wire:click="cancelGeneration({{ $latest->id }})" wire:confirm="این تولید لغو شود؟">لغو</button>
                            </div>
                        @elseif ($latest->status === 'failed')
                            <div class="ai-ca-status failed">ناموفق: {{ $latest->error }}</div>
                        @elseif ($latest->status === 'completed')
                            <div class="ai-ca-preview">
                                @foreach ($latest->result as $item)
                                    <div class="qa"><strong>→ {{ $this->resolveTargetLabel($item['type'], $item['id']) }}</strong>«{{ $item['anchor_text'] }}» — {{ $item['reason'] }}</div>
                                @endforeach
                            </div>
                            @if ($latest->applied_at)
                                <div class="ai-ca-status">به‌عنوان پیشنهادهای در انتظار افزوده شد {{ $latest->applied_at->diffForHumans() }}</div>
                            @else
                                <x-filament::button size="xs" wire:click="applyInternalLinkSuggestions({{ $latest->id }})" wire:confirm="این‌ها به‌عنوان پیشنهادهای در انتظار در مرکز لینک‌سازی داخلی افزوده شوند؟">
                                    افزودن به‌عنوان پیشنهاد در انتظار
                                </x-filament::button>
                            @endif
                        @endif
                    @endif
                </div>
            @endif

            @if (isset($this->suggestionFields['external_links']))
                <div class="ai-ca-card">
                    <h3>پیشنهادهای لینک خارجی</h3>
                    <x-filament::button size="xs" color="gray" wire:click="generateField('external_links', 'generate')" wire:loading.attr="disabled">
                        تولید
                    </x-filament::button>

                    @php($latest = $this->suggestionFields['external_links']['latest'])
                    @if ($latest)
                        @if (in_array($latest->status, ['queued', 'processing']))
                            <div class="ai-ca-status processing">
                                {{ ucfirst($latest->status) }}…
                                <button type="button" class="ai-ca-cancel" wire:click="cancelGeneration({{ $latest->id }})" wire:confirm="این تولید لغو شود؟">لغو</button>
                            </div>
                        @elseif ($latest->status === 'failed')
                            <div class="ai-ca-status failed">ناموفق: {{ $latest->error }}</div>
                        @elseif ($latest->status === 'completed')
                            <div class="ai-ca-preview">
                                @foreach ($this->verifiedExternalLinks as $item)
                                    <div class="qa">
                                        <strong>{{ $item['url'] }} @if ($item['broken']) <span style="color:#b91c1c">(در دسترس نیست)</span> @endif</strong>
                                        «{{ $item['anchor_text'] }}» — {{ $item['reason'] }}
                                    </div>
                                @endforeach
                            </div>
                            <div class="ai-ca-status">فقط پیشنهاد — در صورت تمایل، لینک را دستی در متن قرار دهید.</div>
                        @endif
                    @endif
                </div>
            @endif

            @if (isset($this->suggestionFields['schema']))
                <div class="ai-ca-card">
                    <h3>پیشنهادهای اسکیما</h3>
                    <x-filament::button size="xs" color="gray" wire:click="generateField('schema', 'generate')" wire:loading.attr="disabled">
                        تولید
                    </x-filament::button>

                    @php($latest = $this->suggestionFields['schema']['latest'])
                    @if ($latest)
                        @if (in_array($latest->status, ['queued', 'processing']))
                            <div class="ai-ca-status processing">
                                {{ ucfirst($latest->status) }}…
                                <button type="button" class="ai-ca-cancel" wire:click="cancelGeneration({{ $latest->id }})" wire:confirm="این تولید لغو شود؟">لغو</button>
                            </div>
                        @elseif ($latest->status === 'failed')
                            <div class="ai-ca-status failed">ناموفق: {{ $latest->error }}</div>
                        @elseif ($latest->status === 'completed')
                            <div class="ai-ca-preview"><pre style="white-space:pre-wrap;font-size:.75rem">{{ $latest->result }}</pre></div>
                            <div class="ai-ca-status">فقط پیشنهاد — برای اتصال به قالب به توسعه‌دهنده بسپارید.</div>
                        @endif
                    @endif
                </div>
            @endif

            {{-- موقتاً: تولید تصویرِ hero هنوز منتقل نشده — دکمه حفظ شده ولی handler فقط پیام «گروه بعد» نشان می‌دهد --}}
            <div class="ai-ca-card">
                <h3>تصویر شاخص</h3>

                @if (! $this->canGenerateImages)
                    <div class="ai-ca-current empty">هیچ ارائه‌دهنده‌ی تولید تصویری تنظیم نشده — در «استودیوی هوش مصنوعی ← مسیریابی ← تولید تصویر» یکی تنظیم کنید.</div>
                @else
                    <x-filament::button size="xs" wire:click="generateHeroImage" wire:loading.attr="disabled">
                        ✨ تولید تصویر شاخص
                    </x-filament::button>
                @endif

                @forelse ($this->heroImageGenerations as $imageGeneration)
                    <div class="ai-ca-history-item">
                        @if (in_array($imageGeneration->status, ['queued', 'processing']))
                            <span>{{ ucfirst($imageGeneration->status) }}…</span>
                            <button type="button" class="ai-ca-cancel" wire:click="cancelImageGeneration({{ $imageGeneration->id }})" wire:confirm="این تولید تصویر لغو شود؟">لغو</button>
                        @elseif ($imageGeneration->status === 'failed')
                            <span style="color:#b91c1c">ناموفق: {{ $imageGeneration->error }}</span>
                        @elseif ($imageGeneration->status === 'completed')
                            <span>تولید شد — به‌عنوان تصویر شاخص تنظیم شد</span>
                        @endif
                    </div>
                @empty
                    <div class="ai-ca-current empty">هنوز تصویر شاخصی تولید نشده است.</div>
                @endforelse
            </div>

            {{-- موقتاً: ترجمه هنوز منتقل نشده — دکمه‌ها حفظ شده‌اند ولی handler فقط پیام «گروه بعد» نشان می‌دهد --}}
            <div class="ai-ca-card">
                <h3>ترجمه</h3>
                <div class="ai-ca-modes">
                    @if ($record->locale !== 'en')
                        <button type="button" class="ai-ca-mode-btn" wire:click="translate('en')" wire:loading.attr="disabled">ترجمه به انگلیسی</button>
                    @endif
                    @if ($record->locale !== 'tr')
                        <button type="button" class="ai-ca-mode-btn" wire:click="translate('tr')" wire:loading.attr="disabled">ترجمه به ترکی</button>
                    @endif
                </div>

                @forelse ($this->translations as $translation)
                    <div class="ai-ca-history-item">
                        @if (in_array($translation->status, ['queued', 'processing']))
                            <span>پیش‌نویس {{ strtoupper($translation->mode) }} — {{ $translation->status }}…</span>
                            <button type="button" class="ai-ca-cancel" wire:click="cancelGeneration({{ $translation->id }})" wire:confirm="این ترجمه لغو شود؟">لغو</button>
                        @elseif ($translation->status === 'failed')
                            <span style="color:#b91c1c">پیش‌نویس {{ strtoupper($translation->mode) }} ناموفق: {{ $translation->error }}</span>
                        @elseif ($translation->status === 'completed')
                            <span>پیش‌نویس {{ strtoupper($translation->mode) }} آماده است — «{{ $translation->result['title'] ?? '' }}»</span>
                            @if (! empty($translation->result['edit_url']))
                                <a href="{{ $translation->result['edit_url'] }}" style="color:#2563eb">باز کردن پیش‌نویس</a>
                            @endif
                        @endif
                    </div>
                @empty
                    <div class="ai-ca-current empty">هنوز ترجمه‌ای وجود ندارد — یک پیش‌نویس کاملِ مرتبط می‌سازد که برای بررسی ذخیره می‌شود و هرگز خودکار منتشر نمی‌شود.</div>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</div>

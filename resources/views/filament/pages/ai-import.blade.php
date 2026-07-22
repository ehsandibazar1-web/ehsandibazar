<x-filament-panels::page>
    <form wire:submit="runImport">
        {{ $this->form }}

        <div style="margin-top: 1.5rem;">
            <x-filament::button type="submit" icon="heroicon-o-sparkles">
                ایمپورت
            </x-filament::button>
        </div>
    </form>

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

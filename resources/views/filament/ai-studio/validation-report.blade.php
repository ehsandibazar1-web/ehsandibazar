<div dir="rtl" style="font-size:.88rem;line-height:1.9">
    <div style="display:flex;gap:1.5rem;flex-wrap:wrap;color:#777;margin-bottom:1rem">
        <span><strong>نتیجه:</strong> {{ $log->isRolledBack() ? 'بازگردانده‌شده' : ($log->status === 'imported' ? 'ایمپورت‌شده' : 'ناموفق') }}</span>
        <span><strong>توسط:</strong> {{ $log->user->name ?? '—' }}</span>
        <span><strong>ارائه‌دهنده‌ی AI:</strong> {{ $log->ai_provider ?? '—' }}</span>
        <span><strong>قالب:</strong> {{ $log->format ?? '—' }}</span>
        @if($log->article_title)<span><strong>مقاله:</strong> {{ $log->article_title }}</span>@endif
        <span><strong>FAQها:</strong> {{ $log->faq_count }}</span>
        <span><strong>تصاویر:</strong> {{ $log->image_count }}</span>
    </div>

    @if($log->isRolledBack())
        <div style="border:1px solid #f5d0a9;background:#fdf6ec;border-radius:8px;padding:.7rem 1rem;margin-bottom:1rem;color:#92600c">
            در {{ optional($log->rolled_back_at)->format('Y-m-d H:i') }}
            توسطِ {{ $log->rolledBackBy->name ?? '—' }} بازگردانده شد — مقاله‌ی ایمپورت‌شده حذف شد.
        </div>
    @endif

    @if(!empty($log->errors))
        <h4 style="font-weight:700;margin-bottom:.25rem;color:#b91c1c">مشکلاتِ یافت‌شده ({{ count($log->errors) }})</h4>
        <ul style="padding-right:1.25rem;list-style:disc;color:#b91c1c;margin-bottom:1rem">
            @foreach($log->errors as $error)<li>{{ is_array($error) ? json_encode($error, JSON_UNESCAPED_UNICODE) : $error }}</li>@endforeach
        </ul>
    @endif

    @if(!empty($log->warnings))
        <h4 style="font-weight:700;margin-bottom:.25rem;color:#92700c">یادداشت‌ها ({{ count($log->warnings) }})</h4>
        <ul style="padding-right:1.25rem;list-style:disc;color:#92700c;margin-bottom:1rem">
            @foreach($log->warnings as $warning)<li>{{ is_array($warning) ? json_encode($warning, JSON_UNESCAPED_UNICODE) : $warning }}</li>@endforeach
        </ul>
    @endif

    @if(empty($log->errors) && empty($log->warnings))
        <p style="color:#15803d">✓ هیچ مشکل یا یادداشتی نبود — همه‌چیز درست نگاشته شد.</p>
    @endif
</div>

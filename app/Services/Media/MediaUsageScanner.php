<?php

namespace App\Services\Media;

use App\Models\Media;

/**
 * کجاهای سایت از یک فایل استفاده می‌کنند (بر اساس تطبیقِ disk_path، نه کلید خارجی).
 *
 * موقتاً خنثی: مدل‌های محتوا (Article/Page/SiteSetting) هنوز به شکلِ همگرا منتقل نشده‌اند
 * (موج ۴ و ۶). تا آن زمان هیچ محتوایی به رسانه ارجاع نمی‌دهد، پس «استفاده‌نشده» پاسخِ درست است.
 * وقتی آن ماژول‌ها آمدند، همان منطقِ تطبیقِ disk_path (مثل نسخه‌ی سایت انگلیسی) اینجا برمی‌گردد و
 * صفحه‌ی Media Library بدونِ تغییر، هشدارِ «در حال استفاده» را نشان می‌دهد.
 */
class MediaUsageScanner
{
    /**
     * @return array<int, array{type: string, label: string, field: string}>
     */
    public function scan(Media $media): array
    {
        // TODO(موج ۴/۶): وقتی Article/Page/SiteSetting همگرا منتقل شدند، اسکنِ disk_path را برگردان.
        return [];
    }
}

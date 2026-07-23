<?php

namespace App\Models;

use App\Jobs\IndexKnowledgeContent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class KnowledgeEntryAttachment extends Model
{
    public const SOURCE_FILE = 'file';

    public const SOURCE_URL = 'url';

    public const EXTRACTION_PENDING = 'pending';

    public const EXTRACTION_EXTRACTED = 'extracted';

    public const EXTRACTION_FAILED = 'failed';

    protected $fillable = [
        'knowledge_entry_id', 'disk_path', 'original_filename', 'mime_type', 'size',
        'source_type', 'source_url', 'extraction_status', 'extracted_text', 'extracted_at', 'extraction_error',
    ];

    protected $attributes = [
        'source_type' => self::SOURCE_FILE,
        'extraction_status' => self::EXTRACTION_PENDING,
    ];

    protected function casts(): array
    {
        return [
            'extracted_at' => 'datetime',
        ];
    }

    // فقط روی created() صف می‌شود، نه هر save(). فعلاً IndexingService یک stub است پس این دیسپچ
    // یک no-op بی‌ضرر است؛ فایلِ آپلودشده ذخیره می‌شود ولی استخراج/embedding انجام نمی‌شود.
    protected static function booted(): void
    {
        static::created(function (KnowledgeEntryAttachment $attachment) {
            dispatch(new IndexKnowledgeContent($attachment));
        });
    }

    public function knowledgeEntry(): BelongsTo
    {
        return $this->belongsTo(KnowledgeEntry::class);
    }

    public function chunks(): MorphMany
    {
        return $this->morphMany(KnowledgeChunk::class, 'chunkable');
    }

    public function isUrlSource(): bool
    {
        return $this->source_type === self::SOURCE_URL;
    }

    public function url(): string
    {
        if ($this->isUrlSource()) {
            return (string) $this->source_url;
        }

        return asset('storage/'.$this->disk_path);
    }

    // پس از آپلود توسط Filament\Forms\Components\FileUpload (که فقط مسیرهای دیسک را برمی‌گرداند،
    // نه یک شیء فایل)، برای هر مسیر یک ردیف واقعی می‌سازد — از صفحه‌های Create/EditKnowledgeEntry
    // فراخوانی می‌شود تا این منطق یک‌بار نوشته شود، نه در هر دو صفحه.
    public static function createManyFromDiskPaths(KnowledgeEntry $entry, array $paths): void
    {
        foreach ($paths as $path) {
            if (! Storage::disk('public')->exists($path)) {
                continue;
            }

            $entry->attachments()->create([
                'disk_path' => $path,
                'original_filename' => basename($path),
                'mime_type' => Storage::disk('public')->mimeType($path) ?: null,
                'size' => Storage::disk('public')->size($path) ?: null,
            ]);
        }
    }

    // «صفحه‌ی وب» — پیوستی بدون فایل روی دیسک، فقط یک URL. فعلاً استخراج/ایندکس stub است، پس
    // URL صرفاً ذخیره می‌شود. disk_path رشته‌ی خالی است نه NULL (ستون موجود nullable نیست).
    public static function createFromUrl(KnowledgeEntry $entry, string $url): self
    {
        return $entry->attachments()->create([
            'disk_path' => '',
            'original_filename' => $url,
            'source_type' => self::SOURCE_URL,
            'source_url' => $url,
        ]);
    }
}

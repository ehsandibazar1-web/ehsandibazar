<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * یک قطعه‌ی قابل‌بازیابی از یک KnowledgeEntry (فیلد content خودش) یا یک KnowledgeEntryAttachment
 * (متن استخراج‌شده از فایل/صفحه‌ی وب) به‌همراه بردار embedding آن.
 *
 * توجه (نسخه‌ی فارسی): چون مسیر embedding فعلاً stub است، این جدول در عمل خالی می‌ماند —
 * ساختار آن اکنون ساخته می‌شود تا وقتی embedding واقعی آمد، آماده باشد (forward-compatible).
 */
class KnowledgeChunk extends Model
{
    protected $fillable = [
        'chunkable_type', 'chunkable_id', 'knowledge_entry_id', 'chunk_index',
        'text', 'char_count', 'embedding', 'embedding_model', 'embedding_dims', 'locale',
    ];

    protected function casts(): array
    {
        return [
            'embedding' => 'array',
        ];
    }

    public function chunkable(): MorphTo
    {
        return $this->morphTo();
    }

    public function knowledgeEntry(): BelongsTo
    {
        return $this->belongsTo(KnowledgeEntry::class);
    }
}

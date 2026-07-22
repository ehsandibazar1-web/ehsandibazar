<?php

namespace App\Models;

use App\Model\Article;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * ردیفِ تاریخچه‌ی یک ایمپورت (پنل «AI Import»). هر ایمپورتِ موفق یا ناموفق یک ردیف اینجا دارد.
 * پورت‌شده از سایتِ انگلیسی با تطبیقِ namespace: Article → App\Model\Article، User → App\User.
 */
class ImportLog extends Model
{
    protected $fillable = [
        'user_id', 'source', 'ai_provider', 'format', 'status',
        'errors', 'warnings', 'article_id', 'article_title', 'locale',
        'faq_count', 'image_count', 'rolled_back_at', 'rolled_back_by',
    ];

    protected $casts = [
        'errors' => 'array',
        'warnings' => 'array',
        'rolled_back_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function rolledBackBy()
    {
        return $this->belongsTo(User::class, 'rolled_back_by');
    }

    public function isRolledBack(): bool
    {
        return $this->rolled_back_at !== null;
    }

    // فقط ایمپورتِ موفقِ بازنگردانده‌شده‌ای که مقاله‌اش هنوز وجود دارد قابلِ بازگردانی است.
    public function canRollBack(): bool
    {
        return $this->status === 'imported'
            && ! $this->isRolledBack()
            && $this->article_id !== null
            && $this->article()->exists();
    }
}

<?php

namespace App\Models;

use App\Model\Article;
use App\Model\Page;
use App\Model\Tag;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * یک کارتِ برنامه‌ریزِ محتوا — از یک ایده‌ی محض (بدونِ Article/Page واقعی) تا انتشار. پورت از سایتِ
 * انگلیسی، تطبیق‌یافته به اسکیمای زنده‌ی فارسی: locale→lang، status رشته→بولین (۰ پیش‌نویس)،
 * user_id الزامیِ مقاله تنظیم می‌شود، contentable_type با aliasِ مورفی (article/page) ذخیره می‌شود.
 * اعلان‌ها (که در انگلیسی به NotificationPreference وابسته‌اند) در این پورت حذف شده‌اند؛ تاریخچه‌ی
 * مراحل (پایه‌ی آمار) حفظ می‌شود. هرگز خودکار محتوا تولید نمی‌کند — بدنه خالی می‌ماند تا ادمین/AI پرش کند.
 */
class ContentPlan extends Model
{
    use LogsActivity;

    public const PRIORITY_LOW = 'low';

    public const PRIORITY_MEDIUM = 'medium';

    public const PRIORITY_HIGH = 'high';

    public const PRIORITY_CRITICAL = 'critical';

    protected $fillable = [
        'title', 'locale', 'content_type', 'contentable_type', 'contentable_id',
        'category', 'workflow_stage_id', 'priority', 'author_id', 'assigned_to',
        'planned_publish_at', 'due_at', 'deadline_notified_at', 'checklist_state', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'planned_publish_at' => 'datetime',
            'due_at' => 'datetime',
            'deadline_notified_at' => 'datetime',
            'checklist_state' => 'array',
        ];
    }

    protected static function booted(): void
    {
        // مهلتِ جدید = هشدارِ جدید.
        static::saving(function (ContentPlan $plan) {
            if ($plan->isDirty('due_at')) {
                $plan->deadline_notified_at = null;
            }
        });
    }

    public function workflowStage(): BelongsTo
    {
        return $this->belongsTo(WorkflowStage::class);
    }

    public function contentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ContentTask::class)->orderBy('sort_order');
    }

    public function stageTransitions(): HasMany
    {
        return $this->hasMany(ContentPlanStageTransition::class)->latest('id');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    // تاریخِ مؤثرِ انتشار برای تقویم — اگر contentable واقعی هست، published_atِ خودش مرجع است.
    public function effectivePublishDate(): ?Carbon
    {
        $published = $this->contentable?->published_at;

        return $published ? Carbon::parse($published) : $this->planned_publish_at;
    }

    /**
     * تغییرِ مرحله + ثبتِ تاریخچه + مادیت‌بخشیِ خودکار هنگامِ رسیدن به AI Draft. اعلان‌ها در این پورت حذف شده‌اند.
     */
    public function moveToStage(WorkflowStage $stage, ?User $actor = null): void
    {
        if ($this->workflow_stage_id === $stage->id) {
            return;
        }

        $fromStageId = $this->workflow_stage_id;

        DB::transaction(function () use ($stage, $actor, $fromStageId) {
            $this->update(['workflow_stage_id' => $stage->id]);

            ContentPlanStageTransition::create([
                'content_plan_id' => $this->id,
                'from_stage_id' => $fromStageId,
                'to_stage_id' => $stage->id,
                'changed_by' => $actor?->id,
            ]);

            if ($stage->slug === WorkflowStage::STAGE_AI_DRAFT && ! $this->contentable_id) {
                $this->materializeContent();
            }
        });
    }

    /**
     * ساختِ رکوردِ واقعیِ Article/Page از روی این ایده — فقط یک‌بار. بدنه خالی می‌ماند (تولیدِ خودکار
     * ممنوع). تطبیقِ فارسی: lang، status=0 (پیش‌نویس)، user_id الزامی، contentable_type = aliasِ مورفی.
     */
    public function materializeContent(): Model
    {
        if ($this->contentable_id && $this->contentable) {
            return $this->contentable;
        }

        $type = $this->content_type ?: 'Article';
        $lang = $this->locale ?: 'fa';
        $modelClass = $type === 'Page' ? Page::class : Article::class;
        $slug = $this->uniqueSlugFor($modelClass, $lang);

        $attributes = [
            'lang' => $lang,
            'title' => $this->title,
            'slug' => $slug,
            'body' => '',
            'status' => 0, // پیش‌نویس (بولین)
            'user_id' => $this->author_id ?? auth()->id() ?? User::query()->orderBy('id')->value('id'),
        ];

        if ($modelClass === Article::class) {
            $attributes['author_name'] = $this->author?->name ?? 'احسان دیبازر';
        }

        $record = $modelClass::create($attributes);

        $tagIds = $this->tags()->pluck('tags.id');
        if ($tagIds->isNotEmpty()) {
            $record->tags()->sync($tagIds);
        }

        $this->update([
            'content_type' => $type,
            'contentable_type' => $record->getMorphClass(), // aliasِ فارسی: article/page
            'contentable_id' => $record->id,
        ]);

        return $record;
    }

    private function uniqueSlugFor(string $modelClass, string $lang): string
    {
        $base = Str::slug($this->title) ?: 'untitled-'.Str::random(6);
        $slug = $base;
        $suffix = 2;

        while ($modelClass::query()->where('lang', $lang)->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'workflow_stage_id', 'priority', 'assigned_to', 'planned_publish_at', 'due_at'])
            ->logOnlyDirty()
            ->useLogName('content_plan');
    }
}

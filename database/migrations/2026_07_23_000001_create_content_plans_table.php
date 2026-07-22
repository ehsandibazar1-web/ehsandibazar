<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدولِ کارت‌های برنامه‌ریزِ محتوا (Content Planner) — پورت از سایتِ انگلیسی، تطبیق‌یافته به
 * users/workflow_stages موجود. contentable چندریختی (Article/Page) تا مرحله‌ی AI Draft خالی است.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('locale', 5)->default('fa');
            $table->string('content_type', 50)->nullable(); // 'Article' | 'Page'
            $table->string('contentable_type', 100)->nullable(); // alias مورفی: article/page
            $table->unsignedBigInteger('contentable_id')->nullable();
            $table->string('category')->nullable();
            $table->foreignId('workflow_stage_id')->constrained()->restrictOnDelete();
            $table->string('priority', 20)->default('medium');
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('planned_publish_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('deadline_notified_at')->nullable();
            $table->json('checklist_state')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['contentable_type', 'contentable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_plans');
    }
};

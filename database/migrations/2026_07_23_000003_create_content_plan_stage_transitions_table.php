<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * تاریخچه‌ی «از مرحله X به Y» — غیرقابل‌تغییر (فقط created_at)، پایه‌ی آمارِ زمانِ انتشار/بازبینی.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_plan_stage_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_stage_id')->nullable()->constrained('workflow_stages')->nullOnDelete();
            $table->foreignId('to_stage_id')->nullable()->constrained('workflow_stages')->nullOnDelete();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->nullable();

            $table->index(['content_plan_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_plan_stage_transitions');
    }
};

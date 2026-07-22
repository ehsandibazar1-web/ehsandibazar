<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * کارهای ریزِ هر ContentPlan (نوشتنِ مقدمه، تصویرِ شاخص، بازبینیِ سئو…). پورت از انگلیسی.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_plan_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('status', 20)->default('pending'); // pending | in_progress | done
            $table->timestamp('due_at')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_tasks');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Every product/article page looks rows up by slug (and filters by status)
 * on every visit; these columns had no index, forcing table scans.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasIndex('products', 'products_slug_index')) {
                $table->index('slug');
            }
            if (! Schema::hasIndex('products', 'products_status_index')) {
                $table->index('status');
            }
        });

        Schema::table('articles', function (Blueprint $table) {
            if (! Schema::hasIndex('articles', 'articles_slug_index')) {
                $table->index('slug');
            }
            if (! Schema::hasIndex('articles', 'articles_status_index')) {
                $table->index('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_slug_index');
            $table->dropIndex('products_status_index');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('articles_slug_index');
            $table->dropIndex('articles_status_index');
        });
    }
};

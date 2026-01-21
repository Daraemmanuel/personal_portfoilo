<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Note: Full-text indexes are already added in 2026_01_15_073958_add_fulltext_indexes_for_search.php
     * This migration adds regular B-tree indexes on title columns for:
     * - Faster exact matches
     * - Faster sorting operations  
     * - Better performance on prefix searches (title LIKE 'query%')
     * 
     * Note: These indexes don't help with contains searches (LIKE '%query%') but
     * are still valuable for other query patterns and general performance.
     */
    public function up(): void
    {
        // Add index on articles.title if it doesn't already exist
        // Using try-catch to handle cases where index might already exist
        try {
            Schema::table('articles', function (Blueprint $table) {
                $table->index('title', 'articles_title_index');
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }

        // Add index on projects.title if it doesn't already exist
        try {
            Schema::table('projects', function (Blueprint $table) {
                $table->index('title', 'projects_title_index');
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropIndex('articles_title_index');
            });
        } catch (\Exception $e) {
            // Index might not exist, continue
        }

        try {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropIndex('projects_title_index');
            });
        } catch (\Exception $e) {
            // Index might not exist, continue
        }
    }
};


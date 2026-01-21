<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Note: JSON column indexing is database-specific:
     * - MySQL: Can use generated columns or full-text indexes
     * - PostgreSQL: Can use GIN indexes on JSONB columns
     * - SQLite: Limited JSON support, indexes may not help much
     * 
     * This migration adds indexes that help with JSON queries where possible.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        // For MySQL, we can add indexes on generated columns for JSON
        if ($driver === 'mysql') {
            // Check if generated columns already exist
            $articlesHasTagsIndex = $this->columnExists('articles', 'tags_index');
            $projectsHasTagsIndex = $this->columnExists('projects', 'tags_index');

            if (!$articlesHasTagsIndex) {
                DB::statement('ALTER TABLE articles ADD COLUMN tags_index VARCHAR(255) GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(tags, "$[0]"))) STORED');
                DB::statement('CREATE INDEX articles_tags_index ON articles(tags_index)');
            }

            if (!$projectsHasTagsIndex) {
                DB::statement('ALTER TABLE projects ADD COLUMN tags_index VARCHAR(255) GENERATED ALWAYS AS (JSON_UNQUOTE(JSON_EXTRACT(tags, "$[0]"))) STORED');
                DB::statement('CREATE INDEX projects_tags_index ON projects(tags_index)');
            }
        } elseif ($driver === 'pgsql') {
            // PostgreSQL: JSON columns are typically JSONB, which supports GIN indexes
            // But we need to check if columns are JSONB first
            try {
                if (!$this->indexExists('articles', 'articles_tags_gin_idx')) {
                    DB::statement('CREATE INDEX articles_tags_gin_idx ON articles USING GIN (tags)');
                }
            } catch (\Exception $e) {
                // Column might not be JSONB, skip
            }

            try {
                if (!$this->indexExists('projects', 'projects_tags_gin_idx')) {
                    DB::statement('CREATE INDEX projects_tags_gin_idx ON projects USING GIN (tags)');
                }
            } catch (\Exception $e) {
                // Column might not be JSONB, skip
            }
        }
        // SQLite: JSON support is limited, indexes won't help much with JSON queries
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            try {
                DB::statement('DROP INDEX articles_tags_index ON articles');
                DB::statement('ALTER TABLE articles DROP COLUMN tags_index');
            } catch (\Exception $e) {
                // Ignore if doesn't exist
            }

            try {
                DB::statement('DROP INDEX projects_tags_index ON projects');
                DB::statement('ALTER TABLE projects DROP COLUMN tags_index');
            } catch (\Exception $e) {
                // Ignore if doesn't exist
            }
        } elseif ($driver === 'pgsql') {
            try {
                DB::statement('DROP INDEX IF EXISTS articles_tags_gin_idx');
            } catch (\Exception $e) {
                // Ignore
            }

            try {
                DB::statement('DROP INDEX IF EXISTS projects_tags_gin_idx');
            } catch (\Exception $e) {
                // Ignore
            }
        }
    }

    /**
     * Check if a column exists.
     */
    private function columnExists(string $table, string $column): bool
    {
        try {
            $connection = Schema::getConnection();
            $database = $connection->getDatabaseName();
            $driver = $connection->getDriverName();

            if ($driver === 'mysql') {
                $result = $connection->select(
                    "SELECT COUNT(*) as count FROM information_schema.columns 
                     WHERE table_schema = ? AND table_name = ? AND column_name = ?",
                    [$database, $table, $column]
                );
                return $result[0]->count > 0;
            } elseif ($driver === 'pgsql') {
                $result = $connection->select(
                    "SELECT COUNT(*) as count FROM information_schema.columns 
                     WHERE table_schema = 'public' AND table_name = ? AND column_name = ?",
                    [$table, $column]
                );
                return $result[0]->count > 0;
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * Check if an index exists.
     */
    private function indexExists(string $table, string $index): bool
    {
        try {
            $connection = Schema::getConnection();
            $database = $connection->getDatabaseName();
            $driver = $connection->getDriverName();

            if ($driver === 'mysql') {
                $result = $connection->select(
                    "SELECT COUNT(*) as count FROM information_schema.statistics 
                     WHERE table_schema = ? AND table_name = ? AND index_name = ?",
                    [$database, $table, $index]
                );
                return $result[0]->count > 0;
            } elseif ($driver === 'pgsql') {
                $result = $connection->select(
                    "SELECT COUNT(*) as count FROM pg_indexes 
                     WHERE schemaname = 'public' AND tablename = ? AND indexname = ?",
                    [$table, $index]
                );
                return $result[0]->count > 0;
            }
        } catch (\Exception $e) {
            return false;
        }

        return false;
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        // Full-text indexes are database-specific
        if ($driver === 'mysql') {
            // MySQL full-text index
            DB::statement('ALTER TABLE articles ADD FULLTEXT INDEX articles_fulltext (title, excerpt, content)');
            DB::statement('ALTER TABLE projects ADD FULLTEXT INDEX projects_fulltext (title, description)');
        } elseif ($driver === 'pgsql') {
            // PostgreSQL GIN index for full-text search
            DB::statement('CREATE INDEX articles_fulltext_idx ON articles USING GIN (to_tsvector(\'english\', title || \' \' || excerpt || \' \' || content))');
            DB::statement('CREATE INDEX projects_fulltext_idx ON projects USING GIN (to_tsvector(\'english\', title || \' \' || description))');
        }
        // SQLite doesn't support full-text indexes in the same way, so we skip it
        // The LIKE queries will still work, just slower on large datasets
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE articles DROP INDEX articles_fulltext');
            DB::statement('ALTER TABLE projects DROP INDEX projects_fulltext');
        } elseif ($driver === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS articles_fulltext_idx');
            DB::statement('DROP INDEX IF EXISTS projects_fulltext_idx');
        }
    }
};

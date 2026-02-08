<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            DB::statement('ALTER TABLE articles ADD FULLTEXT fulltext_index (title, content, excerpt)');
            DB::statement('ALTER TABLE projects ADD FULLTEXT fulltext_index (title, description)');
            DB::statement('ALTER TABLE skills ADD FULLTEXT fulltext_index (name)');
            DB::statement('ALTER TABLE experiences ADD FULLTEXT fulltext_index (role, company, description)');
        } catch (\Exception $e) {
            // Fulltext might not be supported on all DB drivers
        }
    }

    public function down(): void
    {
        // No down action for now to simplify
    }
};

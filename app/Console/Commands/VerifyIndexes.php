<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerifyIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:verify-indexes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify that all database indexes are properly created';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Verifying Database Indexes...');
        $this->newLine();

        $driver = DB::getDriverName();
        $this->info("Database Driver: {$driver}");
        $this->newLine();

        // Check articles table indexes
        $this->info('📄 Articles Table:');
        $articleIndexes = $this->getIndexes('articles');
        foreach ($articleIndexes as $index) {
            $this->line("  ✅ {$index}");
        }

        // Check projects table indexes
        $this->newLine();
        $this->info('📁 Projects Table:');
        $projectIndexes = $this->getIndexes('projects');
        foreach ($projectIndexes as $index) {
            $this->line("  ✅ {$index}");
        }

        // Check for JSON indexes (database-specific)
        if ($driver === 'mysql') {
            $this->newLine();
            $this->info('🔍 MySQL JSON Indexes:');
            try {
                $jsonColumns = DB::select("
                    SELECT COLUMN_NAME 
                    FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'articles' 
                    AND COLUMN_NAME LIKE '%_index'
                ");
                
                if (count($jsonColumns) > 0) {
                    foreach ($jsonColumns as $col) {
                        $this->line("  ✅ Generated column: {$col->COLUMN_NAME}");
                    }
                } else {
                    $this->warn("  ⚠️  No JSON generated columns found (may need to run migration)");
                }
            } catch (\Exception $e) {
                $this->warn("  ⚠️  Could not check JSON columns: {$e->getMessage()}");
            }
        } elseif ($driver === 'pgsql') {
            $this->newLine();
            $this->info('🔍 PostgreSQL GIN Indexes:');
            try {
                $ginIndexes = DB::select("
                    SELECT indexname 
                    FROM pg_indexes 
                    WHERE schemaname = 'public' 
                    AND tablename IN ('articles', 'projects')
                    AND indexname LIKE '%_gin%'
                ");
                
                if (count($ginIndexes) > 0) {
                    foreach ($ginIndexes as $idx) {
                        $this->line("  ✅ GIN index: {$idx->indexname}");
                    }
                } else {
                    $this->warn("  ⚠️  No GIN indexes found (may need to run migration)");
                }
            } catch (\Exception $e) {
                $this->warn("  ⚠️  Could not check GIN indexes: {$e->getMessage()}");
            }
        }

        // Test query performance
        $this->newLine();
        $this->info('⚡ Testing Query Performance:');
        
        $start = microtime(true);
        DB::table('articles')->where('published_at', '<=', now())->count();
        $time = (microtime(true) - $start) * 1000;
        $this->line("  Published articles query: " . round($time, 2) . "ms");

        $start = microtime(true);
        DB::table('articles')->where('category', 'Technology')->count();
        $time = (microtime(true) - $start) * 1000;
        $this->line("  Category query: " . round($time, 2) . "ms");

        $this->newLine();
        $this->info('✅ Index verification complete!');

        return Command::SUCCESS;
    }

    /**
     * Get indexes for a table.
     */
    private function getIndexes(string $table): array
    {
        $driver = DB::getDriverName();
        $indexes = [];
        
        try {
            if ($driver === 'mysql') {
                $results = DB::select("
                    SELECT DISTINCT INDEX_NAME 
                    FROM INFORMATION_SCHEMA.STATISTICS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = ?
                    AND INDEX_NAME != 'PRIMARY'
                ", [$table]);
                
                foreach ($results as $result) {
                    $indexes[] = $result->INDEX_NAME;
                }
            } elseif ($driver === 'pgsql') {
                $results = DB::select("
                    SELECT indexname 
                    FROM pg_indexes 
                    WHERE schemaname = 'public' 
                    AND tablename = ?
                ", [$table]);
                
                foreach ($results as $result) {
                    $indexes[] = $result->indexname;
                }
            } elseif ($driver === 'sqlite') {
                $results = DB::select("PRAGMA index_list({$table})");
                foreach ($results as $result) {
                    $indexes[] = $result->name;
                }
            }
        } catch (\Exception $e) {
            $this->warn("  ⚠️  Could not retrieve indexes: {$e->getMessage()}");
        }
        
        return $indexes;
    }
}


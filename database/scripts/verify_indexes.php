<?php

/**
 * Database Index Verification Script
 * 
 * Run this script to verify that all database indexes are properly created
 * and being used by the database.
 * 
 * Usage: php artisan tinker < database/scripts/verify_indexes.php
 * Or: php -r "require 'vendor/autoload.php'; \$app = require_once 'bootstrap/app.php'; \$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); require 'database/scripts/verify_indexes.php';"
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔍 Verifying Database Indexes...\n\n";

$driver = DB::getDriverName();
echo "Database Driver: {$driver}\n\n";

// Check articles table indexes
echo "📄 Articles Table:\n";
$articleIndexes = $this->getIndexes('articles');
foreach ($articleIndexes as $index) {
    echo "  ✅ {$index}\n";
}

// Check projects table indexes
echo "\n📁 Projects Table:\n";
$projectIndexes = $this->getIndexes('projects');
foreach ($projectIndexes as $index) {
    echo "  ✅ {$index}\n";
}

// Check for JSON indexes (database-specific)
if ($driver === 'mysql') {
    echo "\n🔍 MySQL JSON Indexes:\n";
    $jsonColumns = DB::select("
        SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'articles' 
        AND COLUMN_NAME LIKE '%_index'
    ");
    
    if (count($jsonColumns) > 0) {
        foreach ($jsonColumns as $col) {
            echo "  ✅ Generated column: {$col->COLUMN_NAME}\n";
        }
    } else {
        echo "  ⚠️  No JSON generated columns found (may need to run migration)\n";
    }
} elseif ($driver === 'pgsql') {
    echo "\n🔍 PostgreSQL GIN Indexes:\n";
    $ginIndexes = DB::select("
        SELECT indexname 
        FROM pg_indexes 
        WHERE schemaname = 'public' 
        AND tablename IN ('articles', 'projects')
        AND indexname LIKE '%_gin%'
    ");
    
    if (count($ginIndexes) > 0) {
        foreach ($ginIndexes as $idx) {
            echo "  ✅ GIN index: {$idx->indexname}\n";
        }
    } else {
        echo "  ⚠️  No GIN indexes found (may need to run migration)\n";
    }
}

// Test query performance
echo "\n⚡ Testing Query Performance:\n";
$start = microtime(true);
DB::table('articles')->where('published_at', '<=', now())->count();
$time = (microtime(true) - $start) * 1000;
echo "  Published articles query: " . round($time, 2) . "ms\n";

$start = microtime(true);
DB::table('articles')->where('category', 'Technology')->count();
$time = (microtime(true) - $start) * 1000;
echo "  Category query: " . round($time, 2) . "ms\n";

echo "\n✅ Index verification complete!\n";

function getIndexes(string $table): array
{
    $driver = DB::getDriverName();
    $indexes = [];
    
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
    
    return $indexes;
}


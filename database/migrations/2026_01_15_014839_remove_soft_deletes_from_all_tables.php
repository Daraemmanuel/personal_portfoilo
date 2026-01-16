<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop deleted_at columns only if they exist
        $tables = ['projects', 'skills', 'experiences', 'testimonials', 'articles'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->softDeletes();
        });
    }
};

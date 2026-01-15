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
        Schema::table('projects', function (Blueprint $table) {
            $table->index('sort_order');
            $table->index('created_at');
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->index('sort_order');
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->index('sort_order');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
            $table->dropIndex(['created_at']);
        });
    }
};

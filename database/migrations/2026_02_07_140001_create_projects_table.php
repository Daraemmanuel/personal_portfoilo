<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) { 
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->json('tags')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_archived')->default(false);      
            $table->timestamps();

            $table->index('sort_order', 'projects_sort_order_index');
            $table->index('created_at', 'projects_created_at_index');
            $table->index('title', 'projects_title_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

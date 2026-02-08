<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) { 
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');
            $table->longText('content');
            $table->string('featured_image')->nullable();        
            $table->string('category')->nullable();
            $table->string('series')->nullable();
            $table->integer('series_order')->nullable();
            $table->json('tags')->nullable();
            $table->timestamp('published_at')->nullable();       
            $table->unsignedInteger('views')->default(0);        
            $table->boolean('is_featured')->default(false);      
            $table->timestamps();

            $table->index('slug', 'articles_slug_index');
            $table->index('published_at', 'articles_published_at_index');
            $table->index('category', 'articles_category_index');
            $table->index('is_featured', 'articles_is_featured_index');
            $table->index('title', 'articles_title_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('article_comments')->onDelete('cascade');
            $table->string('reaction_type'); // 'like' or 'helpful'
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            // Prevent duplicate reactions from same IP
            $table->unique(['comment_id', 'reaction_type', 'ip_address'], 'unique_reaction');
            $table->index('comment_id');
            $table->index('reaction_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_reactions');
    }
};

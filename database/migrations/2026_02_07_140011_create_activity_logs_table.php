<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();   
            $table->string('action'); // created, updated, deleted, etc.
            $table->string('model_type'); // Article, Project, etc.
            $table->unsignedBigInteger('model_id')->nullable();  
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id'], 'activity_logs_model_index');
            $table->index('user_id', 'activity_logs_user_id_index');
            $table->index('created_at', 'activity_logs_created_at_index');
            $table->index('action', 'activity_logs_action_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

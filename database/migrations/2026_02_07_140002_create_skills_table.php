<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {   
            $table->id();
            $table->string('name');
            $table->string('icon'); // lucide icon name
            $table->json('items'); // array of skill items       
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('sort_order', 'skills_sort_order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->string('company');
            $table->text('content');
            $table->string('avatar')->nullable();
            $table->integer('rating')->default(5);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('sort_order', 'testimonials_sort_order_index');
            $table->index('rating', 'testimonials_rating_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};

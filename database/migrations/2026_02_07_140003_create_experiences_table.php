<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('role'); 
            $table->string('company'); 
            $table->string('period'); 
            $table->text('description'); 
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('sort_order', 'experiences_sort_order_index');
            $table->index('created_at', 'experiences_created_at_index');
            $table->index('role', 'experiences_role_index');
            $table->index('company', 'experiences_company_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};

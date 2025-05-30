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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
        
            $table->text('summary')->nullable(); // short preview text
            $table->longText('content');

            $table->string('thumbnail_url')->nullable();
            $table->unsignedInteger('duration')->nullable(); // in minutes
            
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->unsignedBigInteger('views')->default(0);

            $table->foreignId('author_id')->constrained('users')->onDelete('cascade'); 
            $table->enum('status', ['draft', 'published'])->default('draft');

            $table->enum('layout_type', ['standard', 'video-focused', 'image-left', 'interactive'])->default('standard');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};

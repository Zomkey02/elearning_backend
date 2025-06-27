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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
        
            $table->text('summary')->nullable(); // short preview text
            $table->text('description')->nullable(); // full description

            $table->string('thumbnail_url')->nullable(); //coursecard img
            $table->unsignedInteger('duration')->nullable(); // total duration in minutes
            
            $table->unsignedBigInteger('views')->default(0);

            $table->foreignId('author_id')->constrained('users')->onDelete('cascade'); 
            $table->enum('status', ['draft', 'published'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

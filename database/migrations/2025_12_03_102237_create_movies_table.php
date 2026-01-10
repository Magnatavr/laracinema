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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->year('year')->nullable();
            $table->integer('duration')->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->string('age_rating')->nullable();
            $table->string('poster')->nullable();
            $table->string('banner')->nullable();
            $table->string('url')->nullable();
            $table->string('trailer_url')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};

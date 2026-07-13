<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wiki_articles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->text('extract')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('page_url');
            $table->timestamp('fetched_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wiki_articles');
    }
};
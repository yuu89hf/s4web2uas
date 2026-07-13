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
            
            // FIX: Diubah ke text agar muat URL gambar yang panjangnya kebangetan!
            $table->text('thumbnail_url')->nullable(); 
            
            // BONUS FIX: Diubah ke text juga biar aman dari URL super panjang
            $table->text('page_url'); 
            
            $table->timestamp('fetched_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wiki_articles');
    }
};
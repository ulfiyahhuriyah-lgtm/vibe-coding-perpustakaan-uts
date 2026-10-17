<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit')->nullable();
            $table->unsignedSmallInteger('tahun_terbit')->nullable();
            $table->unsignedSmallInteger('jumlah_halaman')->nullable();
            $table->string('isbn', 30)->nullable();
            $table->string('kategori', 60)->index();
            $table->text('deskripsi')->nullable();
            $table->string('cover')->nullable();       // path di folder public/
            $table->boolean('tersedia')->default(true); // status ketersediaan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

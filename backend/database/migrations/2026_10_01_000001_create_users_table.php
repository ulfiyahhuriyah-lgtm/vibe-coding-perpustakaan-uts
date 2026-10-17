<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();   // NIM mahasiswa
            $table->string('name');                     // nama mahasiswa (dipakai saat login)
            $table->string('role', 20)->default('mahasiswa');
            $table->char('api_token', 64)->nullable()->unique(); // hash SHA-256 dari token
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

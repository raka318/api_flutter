<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel collections (Tabel 2 - Anak) yang berelasi ke users dan categories.
     */
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            // Isolasi kepemilikan data berdasarkan user yang login
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Foreign Key ke Tabel 1 (categories)
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('title');
            $table->text('note')->nullable();
            $table->string('rating')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel collections saat rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
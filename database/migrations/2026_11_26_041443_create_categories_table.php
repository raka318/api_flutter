<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel categories (Tabel 1 - Induk) yang terikat ke user_id.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users, otomatis terhapus jika user dihapus
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel categories saat rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel watchlists.
     */
    public function up(): void
    {
        Schema::create('watchlists', function (Blueprint $table) {$table->id();
            // Relasi ke tabel users, otomatis terhapus jika user dihapus
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Menyimpan UUID film dari Public API Studio Ghibli
            $table->string('film_id');
            // Menyimpan detail dasar film agar tidak perlu fetch ulang ke API Ghibli
            $table->string('title');$table->string('image');
            $table->string('director');$table->string('rt_score');
            // Catatan pribadi user untuk kebutuhan fitur Update/Edit
            $table->text('personal_note')->nullable();$table->timestamps();

            // Mencegah satu user menyimpan film yang sama lebih dari sekali
            $table->unique(['user_id', 'film_id']);
        });
    }

    /**
     * Membatalkan migrasi (menghapus tabel watchlists).
     */
    public function down(): void
    {
        Schema::dropIfExists('watchlists');
    }
};
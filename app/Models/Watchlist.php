<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Watchlist extends Model
{
    use HasFactory;

    // Kolom yang diizinkan untuk diisi secara massal (mass assignment)
    protected $fillable = [
        'user_id',
        'film_id',
        'title',
        'image',
        'director',
        'rt_score',
        'personal_note',
    ];

    /**
     * Relasi bahwa setiap item watchlist dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
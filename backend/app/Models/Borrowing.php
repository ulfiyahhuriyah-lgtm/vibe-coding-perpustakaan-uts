<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    public const STATUS_DIPINJAM = 'dipinjam';
    public const STATUS_DIKEMBALIKAN = 'dikembalikan';

    public const MAX_ACTIVE = 3;      // BR-01
    public const LOAN_DAYS = 7;       // BR-03

    protected $fillable = [
        'user_id', 'book_id', 'tanggal_pinjam', 'jatuh_tempo', 'tanggal_kembali', 'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'jatuh_tempo' => 'date',
            'tanggal_kembali' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function toApi(): array
    {
        $terlambat = $this->status === self::STATUS_DIPINJAM
            && $this->jatuh_tempo->lt(now()->startOfDay());

        return [
            'id' => $this->id,
            'book_id' => $this->book_id,
            'book' => $this->relationLoaded('book') && $this->book ? [
                'id' => $this->book->id,
                'judul' => $this->book->judul,
                'penulis' => $this->book->penulis,
                'cover_url' => $this->book->cover ? asset($this->book->cover) : null,
            ] : null,
            'tanggal_pinjam' => $this->tanggal_pinjam->toDateString(),
            'jatuh_tempo' => $this->jatuh_tempo->toDateString(),
            'tanggal_kembali' => $this->tanggal_kembali?->toDateString(),
            'status' => $this->status,
            'terlambat' => $terlambat,
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'kode', 'judul', 'penulis', 'penerbit', 'tahun_terbit', 'jumlah_halaman',
        'isbn', 'kategori', 'deskripsi', 'cover', 'tersedia',
    ];

    protected function casts(): array
    {
        return ['tersedia' => 'boolean'];
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /** Bentuk data buku yang dikirim ke client. */
    public function toApi(): array
    {
        return [
            'id' => $this->id,
            'kode' => $this->kode,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun_terbit' => $this->tahun_terbit,
            'jumlah_halaman' => $this->jumlah_halaman,
            'isbn' => $this->isbn,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'cover_url' => $this->cover ? asset($this->cover) : null,
            'tersedia' => $this->tersedia,
            'status' => $this->tersedia ? 'Tersedia' : 'Tidak Tersedia',
        ];
    }
}

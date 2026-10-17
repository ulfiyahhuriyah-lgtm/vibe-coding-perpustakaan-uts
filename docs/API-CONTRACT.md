# API Contract — API Perpustakaan

**Base URL:** `http://127.0.0.1:8000/api` (lokal, `php artisan serve`)
**Format:** HTTP/JSON. Semua response berupa JSON.
**Autentikasi:** header `Authorization: Bearer <token>` (token didapat dari login) untuk endpoint bertanda 🔒.

## Format error
```json
{ "message": "Penjelasan singkat" }
```
Error validasi (422) dari Laravel juga memuat `"errors": { "field": ["..."] }`.

| Kode | Arti di API ini |
|---|---|
| 200 / 201 | Berhasil / berhasil dibuat |
| 401 | Token tidak ada/tidak valid, atau login salah |
| 403 | Mengakses transaksi milik orang lain |
| 404 | Data tidak ditemukan |
| 409 | Konflik status (buku tidak tersedia, sudah dikembalikan) |
| 422 | Validasi gagal / melewati batas 3 buku aktif |

---

## Auth

### POST `/auth/login`
Login dengan NIM dan nama mahasiswa (nama tidak peka huruf besar/kecil).
- Header: `Content-Type: application/json`
- Body: `{ "nim": "2441919054", "nama": "Huriyah Ulfiyah" }`
- Validasi: `nim` dan `nama` wajib diisi.

**200**
```json
{ "message": "Login berhasil.", "token": "Ab12...", "token_type": "Bearer",
  "user": { "id": 3, "username": "2441919054", "name": "Huriyah Ulfiyah", "role": "mahasiswa" } }
```
**401** `{ "message": "NIM dan Nama tidak sesuai." }` · **422** body kosong.

### POST `/auth/logout` 🔒
Mencabut token. **200** `{ "message": "Logout berhasil." }` · **401** jika token tidak valid.

### GET `/auth/me` 🔒
**200** `{ "user": { "id": 3, "username": "2441919054", "name": "Huriyah Ulfiyah", "role": "mahasiswa" } }`

---

## Books (publik, tanpa login)

### GET `/books`
Daftar buku dengan pagination.
| Query | Keterangan |
|---|---|
| `q` | cari di judul, penulis, kategori |
| `kategori` | saring persis berdasarkan kategori |
| `status` | `tersedia` atau `dipinjam` (selain itu → 422) |
| `sort` | `terbaru` (default) atau `judul` |
| `per_page` | 1–50, default 12 |
| `page` | nomor halaman |

**200**
```json
{ "data": [ { "id": 2, "kode": "B002", "judul": "Atomic Habits", "penulis": "James Clear",
    "penerbit": "Gramedia Pustaka Utama", "tahun_terbit": 2019, "jumlah_halaman": 320,
    "isbn": "978-602-0002-02-3", "kategori": "Pengembangan Diri", "deskripsi": "...",
    "cover_url": null, "tersedia": true, "status": "Tersedia" } ],
  "meta": { "current_page": 1, "last_page": 1, "per_page": 12, "total": 11 } }
```

### GET `/books/{id}`
**200** `{ "data": { ...objek buku seperti di atas... } }` · **404** `{ "message": "Data tidak ditemukan." }`

### GET `/categories`
**200** `{ "data": [ { "kategori": "Fiksi", "jumlah": 1 } ] }`

### GET `/stats`
**200** `{ "data": { "jumlah_buku": 11, "jumlah_kategori": 8, "jumlah_anggota": 5 } }`

---

## Borrowings 🔒 (semua wajib login)

### POST `/borrowings`
Meminjam buku. Body: `{ "book_id": 1 }`

Business rule: BR-01 maks. 3 buku aktif · BR-02 buku tidak tersedia ditolak · BR-03 jatuh tempo = tanggal pinjam + 7 hari. Status `books.tersedia` otomatis menjadi `false`.

**201**
```json
{ "message": "Buku berhasil dipinjam.",
  "data": { "id": 1, "book_id": 1, "book": { "id": 1, "judul": "Laut Bercerita", "penulis": "Leila S. Chudori", "cover_url": null },
            "tanggal_pinjam": "2026-10-17", "jatuh_tempo": "2026-10-24", "tanggal_kembali": null,
            "status": "dipinjam", "terlambat": false } }
```
| Kode | Kasus |
|---|---|
| 401 | tanpa/token salah |
| 409 | `Buku sedang tidak tersedia.` |
| 422 | `Batas maksimal 3 buku aktif sudah tercapai.` atau `book_id` kosong/tidak ada |

### GET `/borrowings`
Transaksi milik pengguna yang login. Query `status` = `dipinjam` (aktif) atau `dikembalikan` (riwayat); tanpa query = semua.
**200** `{ "data": [ { ...objek transaksi... } ] }`

### PATCH `/borrowings/{id}/return` (juga menerima PUT)
Mengembalikan buku; `tanggal_kembali` diisi hari ini, `status` = `dikembalikan`, buku kembali `tersedia = true`.
**200** `{ "message": "Buku berhasil dikembalikan.", "data": { ... } }`
| Kode | Kasus |
|---|---|
| 401 | tanpa token |
| 403 | transaksi milik pengguna lain |
| 404 | id tidak ada |
| 409 | `Buku ini sudah dikembalikan.` |

> Catatan: `terlambat` bernilai `true` jika status masih `dipinjam` dan jatuh tempo sudah lewat. Denda **tidak** diimplementasikan (out of scope).

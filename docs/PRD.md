# PRD: API Perpustakaan Terintegrasi
Mata Kuliah Vibe Coding · UTS Kelompok

## 1. Informasi Proyek
| Item | Isi |
|---|---|
| Nama proyek | Perpustakaan SMK Negeri 1 — Layanan Backend Terintegrasi |
| Anggota (nama dan NIM) | Huriyah Ulfiyah — 2441919054 · Ridho — 2441919061 · Jayanti — 2441919023 · Yuliani — 2441919005 · Sri — 2441919022 |
| Versi dan tanggal | 1.0 — 17 Oktober 2026 |
| Link repository | [isi] |

## 2. Ringkasan
Backend API perpustakaan berbasis Laravel yang memungkinkan mahasiswa login, melihat katalog buku, meminjam, dan mengembalikan buku. Data disimpan di database MySQL dan semua endpoint diuji lewat Postman. Sistem ini adalah kelanjutan dari prototipe microservice Tugas 3 yang datanya masih in-memory.

## 3. Tujuan
- Proyek Tugas 3 berkembang menjadi satu backend API Laravel yang terhubung ke database MySQL.
- Layanan peminjaman dan pengembalian berjalan sesuai BR-01 sampai BR-03.
- Semua endpoint terdokumentasi di `docs/API-CONTRACT.md` dan diuji dengan Postman Collection (`api-test/collection.json`).
- Antarmuka web (Blade) memakai API yang sama, sehingga alur Client → HTTP/JSON → Backend → Database terpenuhi.

## 4. Pengguna dan Peran
| Peran | Apa yang bisa dilakukan |
|---|---|
| Mahasiswa | Login, melihat buku, meminjam, mengembalikan, melihat riwayat peminjaman |

## 5. Ruang Lingkup
**In scope:** autentikasi, katalog buku (daftar, detail, cari/filter), peminjaman dan pengembalian, database, API Contract, pengujian API, antarmuka web sederhana mengikuti desain.
**Out of scope:** banyak server/port (satu backend), denda, notifikasi, wishlist, registrasi akun, panel admin.

## 6. Technology Stack
| Komponen | Pilihan | Alasan |
|---|---|---|
| Backend/framework | Laravel 12 (PHP 8.2+) | Dipilih kelompok; migration, validasi, dan ORM mempercepat pengembangan |
| Database | MySQL | Relasional, umum dipakai (XAMPP/Laragon) |
| Format API | HTTP/JSON | Ketentuan tugas |
| Alat uji API | Postman | Mendukung test script & collection |
| AI assistant | Claude | Membantu menyusun kode, dokumen, dan test; hasil direview kelompok |

Perubahan dari Tugas 3: Node.js (2 service, in-memory) → Laravel (1 service, MySQL).

## 7. Kebutuhan Fungsional
**FR-01 Login** — pengguna login dengan NIM dan nama mahasiswa (sesuai data di database) dan menerima Bearer token.
Kriteria: kredensial salah → 401; body kosong → 422; sukses → 200 berisi `token`.

**FR-02 Daftar dan detail buku** — daftar buku (cari, filter kategori/status, urut, pagination) dan detail per ID.
Kriteria: ID tidak ada → 404; daftar memuat `tersedia` dan `status`.

**FR-03 Meminjam buku** — mahasiswa meminjam buku tersedia; ketersediaan diperbarui; jatuh tempo +7 hari.
Kriteria: sukses → 201, buku menjadi `tersedia=false`, `jatuh_tempo = tanggal_pinjam + 7 hari`.

**FR-04 Mengembalikan buku** — mahasiswa mengembalikan pinjamannya.
Kriteria: sukses → 200 dan buku `tersedia=true`; sudah dikembalikan → 409; milik orang lain → 403.

**FR-05 Riwayat/daftar transaksi** — melihat transaksi sendiri, bisa difilter `status`.
Kriteria: tanpa token → 401; hanya transaksi milik user yang login.

## 8. Business Rule
| ID | Aturan |
|---|---|
| BR-01 | Satu mahasiswa maksimal 3 buku aktif (dipinjam dan belum dikembalikan). Pelanggaran → 422 |
| BR-02 | Buku yang tidak tersedia tidak dapat dipinjam. Pelanggaran → 409 |
| BR-03 | Jatuh tempo = tanggal pinjam + 7 hari |

## 9. Model Data
| Tabel | Kolom utama | Relasi |
|---|---|---|
| users | id, username (NIM), name (nama mahasiswa), role, api_token (hash) | 1 user → N borrowings |
| books | id, kode, judul, penulis, penerbit, tahun_terbit, jumlah_halaman, isbn, kategori, deskripsi, cover, tersedia | 1 book → N borrowings |
| borrowings | id, user_id, book_id, tanggal_pinjam, jatuh_tempo, tanggal_kembali, status | FK ke users dan books |

Skema SQL: `database/schema.sql`; migration: `backend/database/migrations/`.

## 10. Rancangan API Endpoint
| Layanan | Method | Endpoint | Fungsi | Perlu login? |
|---|---|---|---|---|
| Auth | POST | /api/auth/login | Login | Tidak |
| Auth | POST | /api/auth/logout | Logout | Ya |
| Auth | GET | /api/auth/me | Data user login | Ya |
| Books | GET | /api/books | Daftar buku | Tidak |
| Books | GET | /api/books/{id} | Detail buku | Tidak |
| Books | GET | /api/categories, /api/stats | Kategori & ringkasan beranda | Tidak |
| Borrowings | POST | /api/borrowings | Membuat peminjaman | Ya |
| Borrowings | GET | /api/borrowings | Daftar transaksi | Ya |
| Borrowings | PATCH/PUT | /api/borrowings/{id}/return | Mengembalikan buku | Ya |

Detail lengkap: `docs/API-CONTRACT.md`.

## 11. Rencana Pengujian API
Collection Postman: `api-test/collection.json` (25 request dengan test script). Isi kolom Status setelah dijalankan.
| ID | Skenario | Hasil yang diharapkan | Status |
|---|---|---|---|
| T-01 | Login akun valid | 200 + token | Lulus |
| T-02 | Login akun salah | 401 (body kosong: 422) | Lulus |
| T-03 | Lihat daftar dan detail buku | 200; ID tidak ada 404 | Lulus |
| T-04 | Pinjam buku tersedia | 201, jatuh tempo +7 hari | Lulus |
| T-05 | Pinjam buku tidak tersedia | 409 | Lulus |
| T-06 | Pinjam buku ke-4 | 422 | Lulus |
| T-07 | Kembalikan buku | 200, status dikembalikan | Lulus |
| T-08 | Cek ketersediaan setelah pinjam & kembali | false setelah pinjam, true setelah kembali | Lulus |

Hasil Collection Runner Postman (17 Oktober 2026): 39 test lulus, 0 gagal.

Test otomatis tambahan: `php artisan test` (`backend/tests/Feature/PerpustakaanApiTest.php`).

## 12. Rencana Penggunaan AI
Pola: Requirement/Context → Prompt → Output AI → Review manusia → Testing → Iterasi.
| No | Pekerjaan | Context | Prompt disimpan di | Hasil review |
|---|---|---|---|---|
| 1 | Membuat backend Laravel + UI | PRD/instruksi UTS, proyek Tugas 3, desain UI | docs/PROMPTS.md | docs/CODE_REVIEW_LOG.md |

## 13. Rencana Implementasi
| No | Tugas | Penanggung jawab | Hari ke- | Selesai? |
|---|---|---|---|---|
| 1 | Migration & seeder | [isi] | 1 | [isi] |
| 2 | Auth API | [isi] | 2 | [isi] |
| 3 | Books API | [isi] | 3 | [isi] |
| 4 | Borrowings API + business rule | [isi] | 4 | [isi] |
| 5 | Postman collection & pengujian | [isi] | 5 | [isi] |
| 6 | API Contract, Code Review Log, README | [isi] | 6 | [isi] |

## 14. Definition of Done
Semua kriteria penerimaan terpenuhi · diuji di Postman termasuk kasus gagal · kode AI direview anggota · API Contract & README diperbarui.

## 15. Risiko dan Pertanyaan Terbuka
| Risiko / Pertanyaan | Rencana penanganan |
|---|---|
| Laravel baru bagi sebagian anggota | Setiap anggota membaca controller & migration; Code Review Log |
| Data uji berubah setelah pengujian | Jalankan `php artisan migrate:fresh --seed` sebelum menjalankan collection |

# Perpustakaan SMK Negeri 1 — API & Web (UTS Vibe Coding)

Evolusi API Perpustakaan (Tugas 3, Node.js microservice in-memory) menjadi **satu backend Laravel** yang terhubung ke **MySQL**, dengan antarmuka web Blade yang memakai API yang sama.

Alur: `Client (Postman / Browser) → HTTP/JSON → Backend Laravel → MySQL`

## Technology Stack
Laravel 12 · PHP 8.2+ · MySQL · Postman · Blade + JavaScript (fetch) untuk antarmuka.

## Struktur
```
ROOT/
├── docs/            PRD.md, API-CONTRACT.md, CODE_REVIEW_LOG.md, PROMPTS.md
├── backend/         proyek Laravel
├── database/        schema.sql (referensi)
├── api-test/        collection.json (Postman)
└── README.md
```

## Kebutuhan
- PHP ≥ 8.2 (ekstensi: mbstring, xml, curl, pdo_mysql), Composer
- MySQL / MariaDB (XAMPP atau Laragon)
- Postman

## Instalasi & Menjalankan
```bash
cd backend
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
```
Buat database kosong bernama `perpustakaan_uts` (phpMyAdmin atau `CREATE DATABASE perpustakaan_uts;`), lalu cek konfigurasi di `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan_uts
DB_USERNAME=root
DB_PASSWORD=
```
Migrasi + data awal, lalu jalankan:
```bash
php artisan migrate --seed
php artisan serve
```
- Web: http://127.0.0.1:8000
- **Base URL API:** `http://127.0.0.1:8000/api`

## Akun & Data Uji
Login memakai **NIM + nama mahasiswa** (tanpa password; nama tidak peka huruf besar/kecil).
| NIM | Nama |
|---|---|
| **2441919054** | Huriyah Ulfiyah (dipakai Postman collection) |
| 2441919061 | Ridho |
| 2441919023 | Jayanti |
| 2441919005 | Yuliani (sudah meminjam Sapiens & Dunia Sophie) |
| 2441919022 | Sri |

Seeder membuat 11 buku; Sapiens (id 3) dan Dunia Sophie (id 4) berstatus tidak tersedia untuk menguji BR-02.

## Pengujian API
1. `php artisan migrate:fresh --seed` (reset data), `php artisan serve`.
2. Postman → Import → `api-test/collection.json`.
3. Variabel `base_url` default `http://127.0.0.1:8000`. Jalankan lewat **Collection Runner secara berurutan** (urutan penting).
4. Pengujian otomatis (opsional): `cd backend && php artisan test`.

Dokumentasi endpoint lengkap: [`docs/API-CONTRACT.md`](docs/API-CONTRACT.md).

## Business Rule
BR-01 maksimal 3 buku aktif · BR-02 buku tidak tersedia tidak bisa dipinjam · BR-03 jatuh tempo = tanggal pinjam + 7 hari.

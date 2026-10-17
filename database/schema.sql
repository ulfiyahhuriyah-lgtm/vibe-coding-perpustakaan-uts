-- Skema MySQL (setara dengan migration Laravel di backend/database/migrations).
-- Cara resmi: `php artisan migrate --seed`. File ini hanya referensi/ERD.
CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,      -- NIM
  name VARCHAR(255) NOT NULL,                -- nama mahasiswa (dipakai saat login)
  role VARCHAR(20) NOT NULL DEFAULT 'mahasiswa',
  api_token CHAR(64) NULL UNIQUE,            -- SHA-256 dari bearer token
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
);

CREATE TABLE books (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode VARCHAR(20) NOT NULL UNIQUE,
  judul VARCHAR(255) NOT NULL,
  penulis VARCHAR(255) NOT NULL,
  penerbit VARCHAR(255) NULL,
  tahun_terbit SMALLINT UNSIGNED NULL,
  jumlah_halaman SMALLINT UNSIGNED NULL,
  isbn VARCHAR(30) NULL,
  kategori VARCHAR(60) NOT NULL, INDEX (kategori),
  deskripsi TEXT NULL,
  cover VARCHAR(255) NULL,
  tersedia TINYINT(1) NOT NULL DEFAULT 1,    -- status ketersediaan
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL
);

CREATE TABLE borrowings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  book_id BIGINT UNSIGNED NOT NULL,
  tanggal_pinjam DATE NOT NULL,
  jatuh_tempo DATE NOT NULL,                 -- tanggal_pinjam + 7 hari
  tanggal_kembali DATE NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'dipinjam',  -- dipinjam | dikembalikan
  created_at TIMESTAMP NULL, updated_at TIMESTAMP NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
  INDEX (user_id, status), INDEX (book_id, status)
);

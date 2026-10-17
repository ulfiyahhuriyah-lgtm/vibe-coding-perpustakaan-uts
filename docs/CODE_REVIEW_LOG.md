# Code Review Log

Review atas kode yang dibuat dengan bantuan AI. **Pastikan setiap anggota membaca & memahami tiap temuan; ubah kolom "Direview oleh" dengan nama pereview.**

| No | Bagian | Aspek | Temuan | Perbaikan/Keputusan | Direview oleh |
|---|---|---|---|---|---|
| 1 | `BorrowingController::store` | Correctness | Draf awal: cek ketersediaan → hitung pinjaman aktif → insert dilakukan terpisah. Dua request bersamaan bisa meminjam buku yang sama atau melewati batas 3 buku (race condition). | Dibungkus `DB::transaction` dengan `lockForUpdate()` pada baris user dan buku, sehingga BR-01 dan BR-02 konsisten. | [isi] |
| 2 | `ForceJsonResponse` (route `/api`) | Correctness | Tanpa header `Accept: application/json`, error validasi Laravel membalas redirect/HTML — membingungkan di Postman. | Ditambah middleware yang memaksa `Accept: application/json` pada group API; handler 404 khusus `/api/*` mengembalikan JSON. | Sri Rahmayanti Sahar |
| 3 | `AuthController::login` + `AuthenticateApiToken` | Maintainability / keamanan | Menyimpan token mentah di database berbahaya jika tabel bocor. Proyek Tugas 3 juga mengirim token lewat query string dan login hanya NIM+nama tanpa password. | Token dikirim lewat header `Bearer`, di database hanya disimpan hash SHA-256. Login tetap NIM + nama sesuai ketentuan Tugas 3 (nama dicocokkan dengan data di tabel `users`, bukan dipercaya dari input). | [isi] |
| 4 | `BorrowingController` (method pengembalian) | Readability | Method bernama `return` memakai kata kunci PHP sehingga membingungkan dibaca. | Diganti `giveBack` dan dirujuk dari route. | [isi] |
| 5 | `DatabaseSeeder` | Correctness | `updateOrCreate` pada buku akan mengembalikan `tersedia = true` setiap seeder dijalankan ulang, padahal buku sedang dipinjam. | Diganti `firstOrCreate` agar data transaksi tetap konsisten dengan status buku. | [isi] |

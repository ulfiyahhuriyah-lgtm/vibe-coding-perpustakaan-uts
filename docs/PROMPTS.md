# Prompt AI yang Dipakai (UTS)

| No | Pekerjaan | Context yang diberikan | Prompt (ringkas) | Hasil review |
|---|---|---|---|---|
| 1 | Membuat backend Laravel + antarmuka | PDF instruksi UTS (PRD + rubrik), zip proyek Tugas 3 (microservice Node.js), gambar desain UI | "Buatkan website ini pakai Laravel, bisa diuji di Postman, desain mengikuti gambar ke-3, dan sesuai ketentuan UTS." | Lihat `docs/CODE_REVIEW_LOG.md` |
| 2 | Perbaikan bug: composer install gagal di PHP 8.3 | Log error composer install (paket symfony v8.1 butuh PHP >= 8.4.1), composer.json, README (PHP ≥ 8.2) | Mengirim log terminal composer install yang gagal dan meminta cara memperbaikinya | `composer config platform.php 8.2.0` lalu `composer update`; diuji ulang dengan migrate --seed dan Postman (39/39 lulus). Commit 655d4b2 |

> Tambahkan prompt lain yang kelompok pakai (mis. saat debugging) beserta hasil reviewnya.

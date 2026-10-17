<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun uji — login dengan NIM + nama mahasiswa (tanpa password)
        $mahasiswa = [
            ['2441919054', 'Huriyah Ulfiyah'],
            ['2441919061', 'Ridho'],
            ['2441919023', 'Jayanti'],
            ['2441919005', 'Yuliani'],
            ['2441919022', 'Sri'],
        ];
        foreach ($mahasiswa as [$nim, $nama]) {
            User::updateOrCreate(['username' => $nim], ['name' => $nama, 'role' => 'mahasiswa']);
        }

        $books = [
            ['B001', 'Laut Bercerita', 'Leila S. Chudori', 'Kepustakaan Populer Gramedia', 2017, 379, 'Fiksi', true, null,
                'Novel tentang aktivis mahasiswa yang hilang dan keluarga yang terus mencari kebenaran.'],
            ['B002', 'Atomic Habits', 'James Clear', 'Gramedia Pustaka Utama', 2019, 320, 'Pengembangan Diri', true, null,
                'Atomic Habits membahas bagaimana perubahan kecil yang konsisten dapat memberikan hasil luar biasa dalam hidup kita. Buku ini memberikan panduan praktis untuk membangun kebiasaan baik, menghilangkan kebiasaan buruk, dan mencapai tujuan jangka panjang.'],
            ['B003', 'Sapiens', 'Yuval Noah Harari', 'Kepustakaan Populer Gramedia', 2017, 544, 'Sejarah', true, null,
                'Sejarah singkat umat manusia dari zaman batu hingga era modern.'],
            ['B004', 'Dunia Sophie', 'Jostein Gaarder', 'Mizan', 2015, 800, 'Filsafat', true, null,
                'Pengantar sejarah filsafat yang dibalut cerita seorang remaja bernama Sophie.'],
            ['B005', 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Lentera Dipantara', 2005, 535, 'Sejarah', true, null,
                'Kisah Minke pada masa kolonial Hindia Belanda.'],
            ['B006', 'Pemrograman Dasar', 'Andi Wijaya', 'Informatika', 2021, 250, 'Pemrograman', true, 'covers/sampul-B001.jpg',
                'Pengantar logika pemrograman, tipe data, percabangan, dan perulangan untuk pemula.'],
            ['B007', 'Struktur Data', 'Siti Rahma', 'Informatika', 2020, 310, 'Pemrograman', true, 'covers/sampul-B002.jpg',
                'Array, linked list, stack, queue, tree, dan graf beserta analisis kompleksitasnya.'],
            ['B008', 'Basis Data', 'Budi Hartono', 'Informatika', 2022, 290, 'Data', true, 'covers/sampul-B003.jpg',
                'Perancangan basis data relasional, normalisasi, dan kueri SQL tingkat dasar hingga menengah.'],
            ['B009', 'Rekayasa Perangkat Lunak', 'Dewi Lestari', 'Informatika', 2023, 340, 'Rekayasa', true, 'covers/sampul-B004.jpg',
                'Siklus hidup perangkat lunak, requirement engineering, pengujian, dan manajemen proyek.'],
            ['B010', 'Jaringan Komputer', 'Rian Saputra', 'Informatika', 2021, 280, 'Jaringan', true, 'covers/sampul-B005.jpg',
                'Model OSI dan TCP/IP, pengalamatan IP, routing, serta dasar keamanan jaringan.'],
            ['B011', 'Kecerdasan Buatan', 'Maya Putri', 'Informatika', 2023, 360, 'Data', true, 'covers/sampul-B006.jpg',
                'Pencarian, representasi pengetahuan, pembelajaran mesin, dan penerapannya.'],
            // --- Koleksi tambahan (sampul asli di public/covers) ---
            ['B012', 'Seporsi Mie Ayam Sebelum Mati', 'Brian Khrisna', 'Grasindo', null, null, 'Fiksi', true, 'covers/sampul-B012.jpg',
                'Novel tentang seseorang yang sedang berada di titik terendah dan menemukan arti hidup lewat hal-hal sederhana, salah satunya semangkuk mie ayam.'],
            ['B013', 'Luka Cita', 'Valerie Patkar', 'Bhuana Sastra', null, null, 'Fiksi', true, 'covers/sampul-B013.jpg',
                'Kisah untuk mereka yang terluka dalam perjalanan menggapai cita-cita.'],
            ['B014', 'Sabtu Bersama Bapak', 'Adhitya Mulya', 'GagasMedia', 2014, null, 'Keluarga', true, 'covers/sampul-B014.jpg',
                'Novel keluarga tentang seorang ayah yang menyiapkan pesan-pesan untuk anak-anaknya sebelum ia tiada.'],
            ['B015', 'Narasi Perihal Ayah', 'Jaquenza Eden', null, null, null, 'Keluarga', true, 'covers/sampul-B015.jpg',
                'Cerita tentang hubungan seorang anak dengan ayahnya.'],
            ['B016', 'Dia Adalah Kakakku', 'Tere Liye', null, null, null, 'Keluarga', true, 'covers/sampul-B016.jpg',
                'Novel tentang kakak beradik dan ikatan keluarga yang diuji oleh keadaan.'],
            ['B017', 'Si Anak Kuat', 'Tere Liye', null, null, null, 'Keluarga', true, 'covers/sampul-B017.jpg',
                'Bagian dari serial anak-anak Mamak karya Tere Liye.'],
            ['B018', 'Si Anak Pemberani', 'Tere Liye', null, null, null, 'Keluarga', true, 'covers/sampul-B018.jpg',
                'Bagian dari serial anak-anak Mamak karya Tere Liye.'],
            ['B019', 'Si Anak Badai', 'Tere Liye', null, null, null, 'Keluarga', true, 'covers/sampul-B019.jpg',
                'Bagian dari serial anak-anak Mamak karya Tere Liye.'],
            ['B020', 'Si Anak Pelangi', 'Tere Liye', null, null, null, 'Keluarga', true, 'covers/sampul-B020.jpg',
                'Bagian dari serial anak-anak Mamak karya Tere Liye.'],
            ['B021', 'Ayahku (Bukan) Pembohong', 'Tere Liye', 'Sabak Grip', null, null, 'Keluarga', true, 'covers/sampul-B021.jpg',
                'Kisah seorang ayah dan cerita-ceritanya yang dikenang oleh anaknya.'],
            ['B022', 'Selamat Tinggal', 'Tere Liye', 'Gramedia Pustaka Utama', 2020, null, 'Fiksi', true, 'covers/sampul-B022.jpg',
                'Novel Tere Liye tentang seorang mahasiswa dan dunia buku bajakan.'],
            ['B023', 'Rasa', 'Tere Liye', null, null, null, 'Fiksi', true, 'covers/sampul-B023.jpg',
                'Novel karya Tere Liye.'],
            ['B024', 'Hello', 'Tere Liye', null, null, null, 'Fiksi', true, 'covers/sampul-B024.jpg',
                'Novel karya Tere Liye.'],
            ['B025', 'Hujan', 'Tere Liye', 'Gramedia Pustaka Utama', 2016, null, 'Fiksi', true, 'covers/sampul-B025.jpg',
                'Novel Tere Liye tentang persahabatan, kehilangan, dan cinta di masa depan.'],
            ['B026', 'Pergi', 'Tere Liye', 'Sabak Grip', null, null, 'Fiksi', true, 'covers/sampul-B026.jpg',
                'Lanjutan serial Bumi, Pulang, dan Pergi karya Tere Liye.'],
            ['B027', 'Tentang Kamu', 'Tere Liye', 'Republika', 2016, null, 'Fiksi', true, 'covers/sampul-B027.jpg',
                'Novel Tere Liye tentang pencarian jejak seorang perempuan bernama Sri Ningsih.'],
        ];

        foreach ($books as $i => [$kode, $judul, $penulis, $penerbit, $tahun, $hal, $kategori, $ada, $cover, $desk]) {
            Book::firstOrCreate(['kode' => $kode], [
                'judul' => $judul, 'penulis' => $penulis, 'penerbit' => $penerbit,
                'tahun_terbit' => $tahun, 'jumlah_halaman' => $hal,
                'isbn' => $i < 11 ? sprintf('978-602-0002-%02d-%d', $i + 1, ($i * 3) % 10) : null,
                'kategori' => $kategori, 'deskripsi' => $desk, 'cover' => $cover, 'tersedia' => $ada,
            ]);
        }

        // Data contoh: Yuliani sedang meminjam Sapiens & Dunia Sophie (buku jadi "Tidak Tersedia")
        if (Borrowing::count() === 0) {
            $yuliani = User::where('username', '2441919005')->first();
            foreach (['B003', 'B004'] as $kode) {
                $book = Book::where('kode', $kode)->first();
                Borrowing::create([
                    'user_id' => $yuliani->id,
                    'book_id' => $book->id,
                    'tanggal_pinjam' => now()->subDays(2)->toDateString(),
                    'jatuh_tempo' => now()->addDays(5)->toDateString(),
                    'status' => Borrowing::STATUS_DIPINJAM,
                ]);
                $book->update(['tersedia' => false]);
            }
        }
    }
}

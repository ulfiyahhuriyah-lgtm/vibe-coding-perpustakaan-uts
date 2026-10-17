@extends('layouts.app')
@section('page', 'home')
@section('content')
<section class="hero">
    <img class="scenery hero-img" src="{{ asset('images/hero-kafe.jpg') }}" alt="" aria-hidden="true">
    <span class="leaf l1">🍃</span><span class="leaf l2">🌿</span><span class="leaf l3">🍃</span><span class="leaf l4">🍀</span>
    <div class="hero-text">
        <h1>selamat datang di<br>perpustakaan kami</h1>
        <p class="hand">membaca membuka dunia ✿</p>
        <p class="hero-sub">Jelajahi koleksi buku dari berbagai kategori dan temukan inspirasi baru setiap hari.</p>
        <div class="hero-actions">
            <a class="btn btn-dark" href="{{ route('katalog') }}">Jelajahi Koleksi →</a>
            <a class="btn btn-glass" href="#cara">Cara Meminjam</a>
        </div>
    </div>
</section>

<section class="bento">
    <div class="bento-col">
        <div class="mini"><span class="mini-ic">📖</span><div><strong id="st-buku">–</strong><small>Jumlah Buku</small></div></div>
        <div class="mini"><span class="mini-ic">🗂️</span><div><strong id="st-kategori">–</strong><small>Kategori</small></div></div>
        <div class="mini"><span class="mini-ic">🧑‍🎓</span><div><strong id="st-anggota">–</strong><small>Anggota Aktif</small></div></div>
    </div>
    <div class="card chips-card">
        <h3 class="label">Jelajah Cepat</h3>
        <a class="chip c1" href="{{ route('katalog') }}">🔍 semua buku</a>
        <a class="chip c2" href="{{ route('katalog') }}?status=tersedia">🔍 yang tersedia</a>
        <a class="chip c3" href="{{ route('katalog') }}?kategori=Pemrograman">🔍 pemrograman</a>
        <a class="chip c4" href="#cara">🔍 cara meminjam</a>
    </div>
    <div class="card quote-card">
        <span class="quote-ic">🌿</span>
        <p class="hand big">baca hari ini,<br>untuk masa depan yang lebih baik</p>
        <small>— tim perpustakaan</small>
    </div>
</section>

<section class="section" id="kategori">
    <div class="section-head"><div><h2>Kategori Buku</h2><p>temukan buku favoritmu berdasarkan kategori</p></div><a class="pill-link" href="{{ route('katalog') }}">Lihat semua →</a></div>
    <div class="cat-grid" id="cat-grid"></div>
</section>

<section class="section">
    <div class="section-head"><div><h2>Buku Terbaru</h2><p>koleksi yang baru saja ditambahkan</p></div><a class="pill-link" href="{{ route('katalog') }}">Lihat semua →</a></div>
    <div class="book-grid" id="latest-grid"></div>
</section>

<section class="section" id="cara">
    <div class="howto">
        <div class="howto-text">
            <h2>Cara Peminjaman<br>Semakin Mudah</h2>
            <p>Ikuti langkah sederhana untuk meminjam buku di perpustakaan kami.</p>
            <a class="btn btn-dark" href="{{ route('katalog') }}">Mulai Cari Buku →</a>
        </div>
        <ol class="steps">
            <li><span>1</span>Cari buku yang diinginkan</li>
            <li><span>2</span>Periksa ketersediaan buku</li>
            <li><span>3</span>Login dan ajukan peminjaman</li>
            <li><span>4</span>Lihat status dan tanggal jatuh tempo</li>
        </ol>
    </div>
</section>
@endsection

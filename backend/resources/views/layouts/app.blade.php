<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Perpustakaan SMK Negeri 1')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body data-page="@yield('page')" data-api="{{ url('/api') }}" data-base="{{ url('/') }}" @yield('body-attrs')>
<div class="blob blob-a"></div><div class="blob blob-b"></div>

<div class="shell">
    <header class="nav">
        <a class="icon-btn" href="{{ route('peminjaman') }}" title="Peminjaman saya" aria-label="Peminjaman saya">👤</a>
        <a class="icon-btn icon-btn-green" href="{{ route('katalog') }}" title="Katalog" aria-label="Katalog">＋</a>
        <nav class="nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog', 'buku') ? 'active' : '' }}">Katalog</a>
            <a href="{{ route('home') }}#kategori">Kategori</a>
            <a href="{{ route('peminjaman') }}" class="{{ request()->routeIs('peminjaman') ? 'active' : '' }}">Peminjaman</a>
        </nav>
        <form class="nav-search" action="{{ route('katalog') }}" method="get">
            <input type="search" name="q" placeholder="Cari judul, penulis...">
            <button type="submit" aria-label="Cari">🔍</button>
        </form>
        <div id="nav-auth"><a class="btn btn-dark btn-sm" href="{{ route('masuk') }}">Masuk</a></div>
    </header>

    <main>@yield('content')</main>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <div class="brand"><span class="brand-logo">📚</span><span><strong>Perpustakaan</strong><small>SMK Negeri 1</small></span></div>
                <p>Membentuk generasi cerdas melalui literasi dan pengetahuan.</p>
            </div>
            <div><h4>Navigasi</h4><a href="{{ route('home') }}">Beranda</a><a href="{{ route('katalog') }}">Katalog Buku</a><a href="{{ route('peminjaman') }}">Peminjaman</a></div>
            <div><h4>Kontak</h4><p>Jl. Pendidikan No. 123, Kota Bandung</p><p>(022) 123-4567</p><p>perpustakaan@smkn1.sch.id</p></div>
        </div>
        <div class="footer-bottom">© 2026 Perpustakaan SMK Negeri 1 · dibuat dengan 🌿 dan secangkir teh</div>
    </footer>
</div>

<div id="toast" class="toast" role="status"></div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

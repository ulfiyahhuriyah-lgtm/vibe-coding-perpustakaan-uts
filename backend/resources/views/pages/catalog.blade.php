@extends('layouts.app')
@section('title', 'Katalog Buku — Perpustakaan')
@section('page', 'catalog')
@section('content')
<section class="section page-head">
    <h1>Katalog Buku</h1>
    <p>Temukan buku yang sesuai dengan minat dan kebutuhanmu.</p>
    <input id="f-q" class="search-big" type="search" placeholder="Cari buku...">
    <div class="filters">
        <label>Kategori<select id="f-kategori"><option value="">Semua Kategori</option></select></label>
        <label>Status<select id="f-status"><option value="">Semua Status</option><option value="tersedia">Tersedia</option><option value="dipinjam">Tidak Tersedia</option></select></label>
        <label>Urutkan<select id="f-sort"><option value="terbaru">Terbaru</option><option value="judul">Judul A–Z</option></select></label>
    </div>
    <div class="book-grid" id="book-grid"></div>
    <p id="empty" class="muted" hidden>Tidak ada buku yang cocok dengan pencarian.</p>
    <div class="pager" id="pager"></div>
</section>
@endsection

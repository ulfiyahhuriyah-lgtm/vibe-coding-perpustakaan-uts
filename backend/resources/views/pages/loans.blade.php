@extends('layouts.app')
@section('title', 'Peminjaman Saya — Perpustakaan')
@section('page', 'loans')
@section('content')
<section class="section page-head">
    <h1>Peminjaman Saya</h1>
    <p>Kelola dan pantau status peminjaman buku kamu.</p>
    <div class="tabs"><button class="tab active" data-status="dipinjam">Aktif</button><button class="tab" data-status="dikembalikan">Riwayat</button></div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>No</th><th>Judul Buku</th><th>Tanggal Pinjam</th><th>Jatuh Tempo</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody id="loan-rows"></tbody>
        </table>
    </div>
    <p id="loan-info" class="muted"></p>
</section>
@endsection

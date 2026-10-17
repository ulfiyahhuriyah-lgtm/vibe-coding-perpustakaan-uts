@extends('layouts.auth')
@section('title', 'Masuk — Perpustakaan')
@section('page', 'login')
@section('content')
<section class="auth-panel">
    <div class="auth-art" aria-hidden="true">
        <img class="auth-img" src="{{ asset('images/login-perpustakaan.jpg') }}" alt="">
        <span class="leaf l1">🍃</span><span class="leaf l2">🌿</span><span class="leaf l3">🍃</span><span class="leaf l4">🍀</span><span class="leaf l5">🍃</span><span class="leaf l6">🌿</span>
        <em class="hand">buku adalah investasi terbaik ♡</em>
    </div>
    <div class="auth-card">
        <div class="brand brand-dark"><span class="brand-logo">📚</span><span><strong>Perpustakaan</strong><small>SMK Negeri 1</small></span></div>
        <h2>Masuk ke Akun Anda</h2>
        <p class="muted">Silakan login untuk melanjutkan</p>
        <form id="login-form">
            <label>NIM
                <input name="nim" required placeholder="Masukkan NIM" inputmode="numeric" autocomplete="username"></label>
            <label>Nama
                <input name="nama" required placeholder="Masukkan nama sesuai data mahasiswa"></label>
            <p id="login-error" class="error" hidden></p>
            <button class="btn btn-dark btn-block" type="submit">Masuk</button>
        </form>
        <p class="muted small">Contoh: NIM <code>2441919054</code>, nama <code>Huriyah Ulfiyah</code></p>
    </div>
</section>
@endsection

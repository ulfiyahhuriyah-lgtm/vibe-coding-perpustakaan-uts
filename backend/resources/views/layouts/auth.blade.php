<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk — Perpustakaan SMK Negeri 1')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@500;700&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="auth-body" data-page="@yield('page')" data-api="{{ url('/api') }}" data-base="{{ url('/') }}">
<div class="blob blob-a"></div><div class="blob blob-b"></div>

{{-- Daun-daun yang berjatuhan di seluruh halaman --}}
@php($daun = ['🍃', '🌿', '🍀', '🍃'])
<div class="page-leaves" aria-hidden="true">
    @foreach (range(1, 18) as $i)
        <span style="left:{{ ($i * 37) % 100 }}%;font-size:{{ 18 + ($i * 5) % 18 }}px;animation-duration:{{ 10 + ($i * 3) % 9 }}s;animation-delay:-{{ ($i * 2) % 14 }}s">{{ $daun[$i % 4] }}</span>
    @endforeach
</div>

{{-- Layout layar penuh khusus login: tanpa navbar dan footer --}}
<main class="auth-screen">
    <a class="auth-back" href="{{ route('home') }}">← Beranda</a>
    @yield('content')
</main>

<div id="toast" class="toast" role="status"></div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>

@extends('layouts.app')
@section('title', 'Detail Buku — Perpustakaan')
@section('page', 'detail')
@section('body-attrs') data-book-id="{{ $bookId }}" @endsection
@section('content')
<section class="section page-head">
    <h1>Detail Buku</h1>
    <div class="detail" id="detail"><p class="muted">Memuat...</p></div>
</section>
@endsection

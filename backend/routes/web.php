<?php

use Illuminate\Support\Facades\Route;

// Halaman antarmuka (Blade). Semua data diambil lewat API /api/* memakai fetch().
Route::view('/', 'pages.home')->name('home');
Route::view('/katalog', 'pages.catalog')->name('katalog');
Route::get('/buku/{id}', fn (int $id) => view('pages.detail', ['bookId' => $id]))->whereNumber('id')->name('buku');
Route::view('/peminjaman', 'pages.loans')->name('peminjaman');
Route::view('/masuk', 'pages.login')->name('masuk');

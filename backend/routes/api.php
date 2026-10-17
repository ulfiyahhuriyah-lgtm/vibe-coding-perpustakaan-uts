<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BorrowingController;
use Illuminate\Support\Facades\Route;

// Auth
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth.token')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Books (publik)
Route::get('books', [BookController::class, 'index']);
Route::get('books/{id}', [BookController::class, 'show'])->whereNumber('id');
Route::get('categories', [BookController::class, 'categories']);
Route::get('stats', [BookController::class, 'stats']);

// Borrowings (wajib login)
Route::middleware('auth.token')->prefix('borrowings')->group(function () {
    Route::get('/', [BorrowingController::class, 'index']);
    Route::post('/', [BorrowingController::class, 'store']);
    Route::patch('{id}/return', [BorrowingController::class, 'giveBack'])->whereNumber('id');
    Route::put('{id}/return', [BorrowingController::class, 'giveBack'])->whereNumber('id');
});

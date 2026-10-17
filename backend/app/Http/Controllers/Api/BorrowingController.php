<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /** GET /api/borrowings?status=dipinjam|dikembalikan — transaksi milik pengguna yang login. */
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'status' => ['nullable', 'in:dipinjam,dikembalikan'],
        ]);

        $query = $request->user()->borrowings()->with('book')->orderByDesc('id');
        if (! empty($data['status'])) {
            $query->where('status', $data['status']);
        }

        return response()->json(['data' => $query->get()->map->toApi()->values()]);
    }

    /** POST /api/borrowings  { "book_id": 1 } */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'book_id' => ['required', 'integer', 'exists:books,id'],
        ]);

        // Transaksi + lock agar dua permintaan bersamaan tidak bisa meminjam buku yang sama
        // atau melewati batas 3 buku aktif.
        $result = DB::transaction(function () use ($request, $data) {
            User::whereKey($request->user()->id)->lockForUpdate()->first();
            $book = Book::whereKey($data['book_id'])->lockForUpdate()->first();

            if (! $book->tersedia) {                                        // BR-02
                return ['error' => 'Buku sedang tidak tersedia.', 'code' => 409];
            }

            if ($request->user()->activeBorrowingsCount() >= Borrowing::MAX_ACTIVE) { // BR-01
                return ['error' => 'Batas maksimal '.Borrowing::MAX_ACTIVE.' buku aktif sudah tercapai.', 'code' => 422];
            }

            $today = now()->startOfDay();
            $borrowing = Borrowing::create([
                'user_id' => $request->user()->id,
                'book_id' => $book->id,
                'tanggal_pinjam' => $today,
                'jatuh_tempo' => $today->copy()->addDays(Borrowing::LOAN_DAYS), // BR-03
                'status' => Borrowing::STATUS_DIPINJAM,
            ]);
            $book->update(['tersedia' => false]);

            return ['borrowing' => $borrowing->load('book')];
        });

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], $result['code']);
        }

        return response()->json([
            'message' => 'Buku berhasil dipinjam.',
            'data' => $result['borrowing']->toApi(),
        ], 201);
    }

    /** PATCH /api/borrowings/{id}/return */
    public function giveBack(Request $request, int $id): JsonResponse
    {
        $result = DB::transaction(function () use ($request, $id) {
            $borrowing = Borrowing::whereKey($id)->lockForUpdate()->firstOrFail();

            if ($borrowing->user_id !== $request->user()->id) {
                return ['error' => 'Transaksi ini bukan milik Anda.', 'code' => 403];
            }
            if ($borrowing->status === Borrowing::STATUS_DIKEMBALIKAN) {
                return ['error' => 'Buku ini sudah dikembalikan.', 'code' => 409];
            }

            $borrowing->update([
                'status' => Borrowing::STATUS_DIKEMBALIKAN,
                'tanggal_kembali' => now()->startOfDay(),
            ]);
            Book::whereKey($borrowing->book_id)->update(['tersedia' => true]);

            return ['borrowing' => $borrowing->load('book')];
        });

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], $result['code']);
        }

        return response()->json([
            'message' => 'Buku berhasil dikembalikan.',
            'data' => $result['borrowing']->toApi(),
        ]);
    }
}

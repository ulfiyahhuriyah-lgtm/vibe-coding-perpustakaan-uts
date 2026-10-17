<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /** GET /api/books?q=&kategori=&status=&sort=&per_page=&page= */
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'kategori' => ['nullable', 'string', 'max:60'],
            'status' => ['nullable', 'in:tersedia,dipinjam'],
            'sort' => ['nullable', 'in:terbaru,judul'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $query = Book::query();

        if (! empty($data['q'])) {
            $q = $data['q'];
            $query->where(function ($w) use ($q) {
                $w->where('judul', 'like', "%{$q}%")
                    ->orWhere('penulis', 'like', "%{$q}%")
                    ->orWhere('kategori', 'like', "%{$q}%");
            });
        }
        if (! empty($data['kategori'])) {
            $query->where('kategori', $data['kategori']);
        }
        if (($data['status'] ?? null) === 'tersedia') {
            $query->where('tersedia', true);
        } elseif (($data['status'] ?? null) === 'dipinjam') {
            $query->where('tersedia', false);
        }

        ($data['sort'] ?? 'terbaru') === 'judul'
            ? $query->orderBy('judul')
            : $query->orderByDesc('id');

        $page = $query->paginate($data['per_page'] ?? 12)->withQueryString();

        return response()->json([
            'data' => $page->getCollection()->map->toApi()->values(),
            'meta' => [
                'current_page' => $page->currentPage(),
                'last_page' => $page->lastPage(),
                'per_page' => $page->perPage(),
                'total' => $page->total(),
            ],
        ]);
    }

    /** GET /api/books/{id} */
    public function show(int $id): JsonResponse
    {
        return response()->json(['data' => Book::findOrFail($id)->toApi()]);
    }

    /** GET /api/categories — daftar kategori beserta jumlah buku. */
    public function categories(): JsonResponse
    {
        $rows = Book::selectRaw('kategori, count(*) as jumlah')
            ->groupBy('kategori')->orderBy('kategori')->get()
            ->map(fn ($r) => ['kategori' => $r->kategori, 'jumlah' => (int) $r->jumlah]);

        return response()->json(['data' => $rows]);
    }

    /** GET /api/stats — ringkasan untuk beranda. */
    public function stats(): JsonResponse
    {
        return response()->json(['data' => [
            'jumlah_buku' => Book::count(),
            'jumlah_kategori' => Book::distinct('kategori')->count('kategori'),
            'jumlah_anggota' => User::where('role', 'mahasiswa')->count(),
        ]]);
    }
}

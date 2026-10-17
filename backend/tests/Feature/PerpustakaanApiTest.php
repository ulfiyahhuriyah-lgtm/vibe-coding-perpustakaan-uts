<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerpustakaanApiTest extends TestCase
{
    use RefreshDatabase;

    private function login(User $user): string
    {
        return $this->postJson('/api/auth/login', ['nim' => $user->username, 'nama' => $user->name])
            ->assertOk()->json('token');
    }

    private function makeUser(): User
    {
        return User::create(['username' => '111', 'name' => 'Uji']);
    }

    private function makeBook(string $kode = 'X1', bool $tersedia = true): Book
    {
        return Book::create(['kode' => $kode, 'judul' => "Buku $kode", 'penulis' => 'Penulis', 'kategori' => 'Uji', 'tersedia' => $tersedia]);
    }

    public function test_t01_login_valid_nama_tidak_peka_huruf_besar(): void
    {
        $user = $this->makeUser();
        $this->postJson('/api/auth/login', ['nim' => '111', 'nama' => 'uji'])
            ->assertOk()->assertJsonStructure(['token', 'user' => ['id', 'username', 'name']]);
    }

    public function test_t02_login_salah_401(): void
    {
        $this->makeUser();
        $this->postJson('/api/auth/login', ['nim' => '111', 'nama' => 'Orang Lain'])->assertStatus(401);
        $this->postJson('/api/auth/login', [])->assertStatus(422);
    }

    public function test_t03_daftar_dan_detail_buku(): void
    {
        $book = $this->makeBook();
        $this->getJson('/api/books')->assertOk()->assertJsonPath('data.0.judul', 'Buku X1');
        $this->getJson('/api/books/'.$book->id)->assertOk()->assertJsonPath('data.tersedia', true);
        $this->getJson('/api/books/9999')->assertStatus(404);
    }

    public function test_borrowings_butuh_login(): void
    {
        $this->getJson('/api/borrowings')->assertStatus(401);
    }

    public function test_t04_pinjam_buku_tersedia_dan_jatuh_tempo_7_hari(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook();
        $token = $this->login($user);

        $res = $this->withToken($token)->postJson('/api/borrowings', ['book_id' => $book->id])->assertCreated();

        $this->assertSame(now()->toDateString(), $res->json('data.tanggal_pinjam'));
        $this->assertSame(now()->addDays(7)->toDateString(), $res->json('data.jatuh_tempo'));
        $this->assertFalse($book->fresh()->tersedia);
    }

    public function test_t05_pinjam_buku_tidak_tersedia_ditolak(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook('X2', false);
        $this->withToken($this->login($user))->postJson('/api/borrowings', ['book_id' => $book->id])->assertStatus(409);
        $this->assertSame(0, Borrowing::count());
    }

    public function test_t06_batas_3_buku_aktif(): void
    {
        $user = $this->makeUser();
        $token = $this->login($user);
        foreach (['A', 'B', 'C'] as $k) {
            $this->withToken($token)->postJson('/api/borrowings', ['book_id' => $this->makeBook($k)->id])->assertCreated();
        }
        $keempat = $this->makeBook('D');
        $this->withToken($token)->postJson('/api/borrowings', ['book_id' => $keempat->id])->assertStatus(422);
        $this->assertTrue($keempat->fresh()->tersedia);
    }

    public function test_t07_t08_kembalikan_buku_dan_status_tersedia_lagi(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook();
        $token = $this->login($user);

        $id = $this->withToken($token)->postJson('/api/borrowings', ['book_id' => $book->id])->json('data.id');
        $this->withToken($token)->patchJson("/api/borrowings/$id/return")->assertOk()->assertJsonPath('data.status', 'dikembalikan');
        $this->assertTrue($book->fresh()->tersedia);

        // dikembalikan dua kali ditolak
        $this->withToken($token)->patchJson("/api/borrowings/$id/return")->assertStatus(409);
        // setelah dikembalikan, boleh dipinjam lagi
        $this->withToken($token)->postJson('/api/borrowings', ['book_id' => $book->id])->assertCreated();
    }

    public function test_tidak_bisa_mengembalikan_pinjaman_orang_lain(): void
    {
        $a = $this->makeUser();
        $b = User::create(['username' => '222', 'name' => 'B']);
        $book = $this->makeBook();
        $id = $this->withToken($this->login($a))->postJson('/api/borrowings', ['book_id' => $book->id])->json('data.id');

        $this->withToken($this->login($b))->patchJson("/api/borrowings/$id/return")->assertStatus(403);
        $this->assertFalse($book->fresh()->tersedia);
    }

    public function test_validasi_book_id(): void
    {
        $token = $this->login($this->makeUser());
        $this->withToken($token)->postJson('/api/borrowings', [])->assertStatus(422);
        $this->withToken($token)->postJson('/api/borrowings', ['book_id' => 9999])->assertStatus(422);
    }
}

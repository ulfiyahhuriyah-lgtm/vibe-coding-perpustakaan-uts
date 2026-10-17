<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['username', 'name', 'role', 'api_token'];

    protected $hidden = ['api_token'];

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowingsCount(): int
    {
        return $this->borrowings()->where('status', Borrowing::STATUS_DIPINJAM)->count();
    }
}

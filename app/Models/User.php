<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'points',
        'phone',
        'address',
        'avatar',
        'role',
        'email_verified_at',
        'failed_login_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'locked_until'      => 'datetime',
        ];
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaffGate(): bool
    {
        return $this->role === 'staff_gate';
    }

    public function isCustomer():bool
    {
        return $this->role === 'customer';
    }

    public function hasRole(string|array $roles):bool
    {
        return in_array($this->role, (array) $roles);
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    // Helper buat fitur account lockout
    public function isLocked(): bool
    {
        return $this->locked_until && now()->lessThan($this->locked_until);
    }

    // Dipanggil tiap kali user gagal login
    public function incrementFailedAttempts(): void
    {
        $this->increment('failed_login_attempts');

        // Kunci akun setelah 5 kali gagal
        if ($this->failed_login_attempts >= 5) {
            $this->update(['locked_until' => now()->addMinutes(15)]);
        }
    }

    // Dipanggil pas user berhasil login
    public function resetFailedAttempts(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'locked_until'          => null,
        ]);
    }

    // Buat nampilin sisa berapa menit lagi akun bakal kebuka
    public function lockoutMinutesRemaining(): int
    {
        if (!$this->isLocked()) {
            return 0;
        }

        return (int) now()->diffInMinutes($this->locked_until, false) + 1;
    }
}

<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'nomor_whatsapp', 'password', 'role', 'status_profesi', 'instansi_asal'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function aksesKelas(): HasMany
    {
        return $this->hasMany(AksesKelas::class);
    }

    public function aksesKelasFor(Kelas $kelas): ?AksesKelas
    {
        return $this->aksesKelas()
            ->where('kelas_id', $kelas->id)
            ->first();
    }

    public function hasActiveAccessTo(Kelas $kelas): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $akses = $this->aksesKelasFor($kelas);

        return $akses !== null && $akses->isAktif();
    }

    public function canAccessCourse(Kelas $kelas): bool
    {
        return $this->hasActiveAccessTo($kelas);
    }

    public function progresMateris(): HasMany
    {
        return $this->hasMany(ProgresMateri::class);
    }

    public function sertifikats(): HasMany
    {
        return $this->hasMany(Sertifikat::class);
    }
}

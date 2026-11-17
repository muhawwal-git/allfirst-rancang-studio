<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Masuk ke Ruang Belajar');
        $response->assertSee('Alamat Email');
        $response->assertSee('Kata Sandi');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->student()->create([
            'email' => 'budi@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'budi@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('home'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        User::factory()->student()->create([
            'email' => 'budi@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'budi@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
        $response->assertSee('Registrasi Peserta Baru');
        $response->assertSee('Nama Lengkap (Sesuai KTP / Untuk Sertifikat)', false);
        $response->assertSee('Mahasiswa / Pelajar');
        $response->assertSee('PENTING: Verifikasi Nama Sertifikat');
    }

    public function test_new_users_can_register_with_allfirst_fields(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Dimas Prakoso, S.T.',
            'email' => 'dimas@example.com',
            'nomor_whatsapp' => '081234567890',
            'status_profesi' => 'Mahasiswa / Pelajar',
            'instansi_asal' => 'Institut Teknologi Bandung',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'name' => 'Dimas Prakoso, S.T.',
            'email' => 'dimas@example.com',
            'nomor_whatsapp' => '081234567890',
            'status_profesi' => 'Mahasiswa / Pelajar',
            'instansi_asal' => 'Institut Teknologi Bandung',
            'role' => 'student',
        ]);
    }

    public function test_registration_validates_required_fields(): void
    {
        $response = $this->post(route('register.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'nomor_whatsapp',
            'status_profesi',
            'password',
        ]);
        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->student()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }

    public function test_authenticated_user_is_redirected_away_from_guest_routes(): void
    {
        $user = User::factory()->student()->create();

        $response = $this->actingAs($user)->get(route('login'));
        $response->assertRedirect(route('home'));

        $response = $this->actingAs($user)->get(route('register'));
        $response->assertRedirect(route('home'));
    }
}

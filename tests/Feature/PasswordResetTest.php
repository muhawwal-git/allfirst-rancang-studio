<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
        $response->assertSee('Lupa Kata Sandi?');
        $response->assertSee('Kirim Tautan Reset Kata Sandi');
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        $user = User::factory()->student()->create([
            'email' => 'student@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'student@example.com',
        ]);

        $response->assertSessionHas('status');
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $response = $this->get(route('password.reset', ['token' => 'dummy-token', 'email' => 'student@example.com']));

        $response->assertOk();
        $response->assertSee('Atur Ulang Kata Sandi');
        $response->assertSee('Simpan Kata Sandi Baru');
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->student()->create([
            'email' => 'student@example.com',
            'password' => Hash::make('old-password'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'student@example.com',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }
}

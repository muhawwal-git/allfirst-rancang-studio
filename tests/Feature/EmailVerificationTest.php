<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_student_is_redirected_to_verification_notice_from_dashboard_and_checkout(): void
    {
        $user = User::factory()->student()->unverified()->create();
        $kelas = Kelas::factory()->create();

        $this->actingAs($user)
            ->get(route('student.dashboard'))
            ->assertRedirect(route('verification.notice'));

        $this->actingAs($user)
            ->get(route('checkout.create', $kelas))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_verification_notice_screen_can_be_rendered_for_unverified_user(): void
    {
        $user = User::factory()->student()->unverified()->create([
            'email' => 'calon.siswa@gmail.com',
        ]);

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertOk();
        $response->assertSee('Verifikasi Email Anda');
        $response->assertSee('calon.siswa@gmail.com');
        $response->assertSee('Kirim Ulang Email Verifikasi');
    }

    public function test_unverified_student_logging_in_is_redirected_to_verification_notice(): void
    {
        User::factory()->student()->unverified()->create([
            'email' => 'belumverif@gmail.com',
            'password' => 'password123',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'belumverif@gmail.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_email_can_be_verified_with_valid_signed_url(): void
    {
        Event::fake([Verified::class]);

        $user = User::factory()->student()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = User::factory()->student()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('email-salah@gmail.com')]
        );

        $this->actingAs($user)->get($verificationUrl)->assertForbidden();
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_unverified_user_can_resend_verification_email(): void
    {
        Notification::fake();

        $user = User::factory()->student()->unverified()->create();

        $response = $this->actingAs($user)
            ->from(route('verification.notice'))
            ->post(route('verification.send'));

        $response->assertRedirect(route('verification.notice'));
        $response->assertSessionHas('status');
        Notification::assertSentTo($user, VerifyEmailNotification::class);
    }
}

<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('Verifikasi Alamat Email — Allfirst Rancang Studio')
            ->greeting('Halo, Peserta Allfirst Rancang Studio!')
            ->line('Terima kasih telah mendaftar di Allfirst Rancang Studio. Sebelum Anda dapat mengakses Dashboard Belajar dan mendaftar kelas online, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini.')
            ->action('Verifikasi Alamat Email', $url)
            ->line('Tautan verifikasi ini berlaku selama 60 menit demi keamanan akun Anda.')
            ->line('Jika Anda tidak merasa membuat akun di Allfirst Rancang Studio, Anda dapat mengabaikan email ini dengan aman.')
            ->salutation("Salam hangat,\nTim Allfirst Rancang Studio");
    }
}

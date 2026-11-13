<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kunci', 'nilai'])]
class PengaturanSitus extends Model
{
    protected $table = 'pengaturan_situs';

    public static function getValue(string $kunci, ?string $default = null): ?string
    {
        return static::query()->where('kunci', $kunci)->value('nilai') ?? $default;
    }

    public static function setValue(string $kunci, ?string $nilai): void
    {
        static::query()->updateOrCreate(
            ['kunci' => $kunci],
            ['nilai' => $nilai]
        );
    }

    /**
     * Get the list of active studio bank accounts for checkout and invoice pages.
     *
     * @return array<int, array{bank: string, nomor_rekening: string, atas_nama: string}>
     */
    public static function getBankAccounts(): array
    {
        $raw = static::getValue('rekening_bank_list');
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $filtered = array_values(array_filter($decoded, function (mixed $item): bool {
                    return is_array($item)
                        && trim((string) ($item['bank'] ?? '')) !== ''
                        && trim((string) ($item['nomor_rekening'] ?? '')) !== ''
                        && trim((string) ($item['atas_nama'] ?? '')) !== '';
                }));

                if (count($filtered) > 0) {
                    return $filtered;
                }
            }
        }

        return config('allfirst.bank_accounts', []);
    }

    /**
     * Get the configured studio admin WhatsApp number.
     */
    public static function getAdminWhatsapp(): string
    {
        $wa = trim((string) static::getValue('whatsapp_studio', ''));
        if ($wa !== '') {
            $cleaned = preg_replace('/[^0-9]/', '', $wa);
            if ($cleaned !== '' && $cleaned !== null) {
                return $cleaned;
            }
        }

        return (string) config('allfirst.admin_whatsapp', '6282212345678');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Setting extends Pivot
{
    protected $table = 'setting';

    protected $guarded = [];

    public static function getValue($key)
    {

        $setting = self::where('key', $key)->first();

        if(!$setting){

            self::create([
                'key' => $key,
                'value' => null
            ]);

            $setting = self::where('key', $key)->first();
        }

        return $setting ? $setting : null;
    }

    public static function getLogoUrl(): ?string
    {
        $value = self::getValue('logo')?->value;

        return $value ? asset('storage/' . $value) : null;
    }

    public static function getMasaAktifUserBulan(): int
    {
        $value = self::getValue('masa_aktif_user_bulan')?->value;

        return filled($value) ? (int) $value : 6;
    }

    public static function isTelegramReminderActive(): bool
    {
        return (bool) self::getValue('telegram_reminder_jam')?->is_active;
    }

    public static function getTelegramReminderJam(): string
    {
        $value = self::getValue('telegram_reminder_jam')?->value;

        return filled($value) ? $value : '07:00';
    }
    public static function isPremiumFeatureActive(): bool
    {
        $setting = self::where('key', 'premium_feature')->first();

        return $setting ? (bool) $setting->is_active : false;
    }

    public static function getDaftarTanggalMulai(): int
    {
        $value = self::getValue('daftar_tanggal_mulai')?->value;

        return filled($value) ? (int) $value : 25;
    }

    public static function getDaftarTanggalSelesai(): int
    {
        $value = self::getValue('daftar_tanggal_selesai')?->value;

        return filled($value) ? (int) $value : 31;
    }

    public static function isDaftarAktif(): bool
    {
        $masterAktif = self::where('key', 'daftar_aktif')->first();

        if ($masterAktif && ! $masterAktif->is_active) {
            return false;
        }

        $today = now();
        $hariIni = (int) $today->format('j');

        $mulai = min(self::getDaftarTanggalMulai(), $today->daysInMonth);
        $selesai = min(self::getDaftarTanggalSelesai(), $today->daysInMonth);

        return $hariIni >= $mulai && $hariIni <= $selesai;
    }

    public static function getNextDaftarOpenDate()
    {
        $mulai = self::getDaftarTanggalMulai();
        $today = now();
        $hariIni = (int) $today->format('j');

        if ($hariIni < $mulai) {
            return $today->copy()->startOfMonth()->addDays(min($mulai, $today->daysInMonth) - 1);
        }

        $bulanDepan = $today->copy()->addMonthNoOverflow()->startOfMonth();

        return $bulanDepan->addDays(min($mulai, $bulanDepan->daysInMonth) - 1);
    }

    public static function getGoogleAnalyticsId(): ?string
    {
        return self::getValue('google_analytics_id')?->value ?: null;
    }

    public static function getFacebookPixelId(): ?string
    {
        return self::getValue('facebook_pixel_id')?->value ?: null;
    }

    public static function getDefaultGelombangId(): ?int
    {
        $value = self::getValue('default_gelombang_id')?->value;

        return filled($value) ? (int) $value : null;
    }
}

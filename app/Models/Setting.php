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
}

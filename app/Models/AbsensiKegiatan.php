<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiKegiatan extends Model
{
    use HasFactory;
    protected $table = "absensi_kegiatan";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function materi_detail()
    {
        return $this->belongsTo(MateriDetail::class);
    }

    // public function kategori_kegiatan()
    // {
    //     return $this->hasManyThrough(KategoriKegiatan::class, Kegiatan::class, 'kategori_id');
    // }


    // public function scopeKelompokTerdaftarKegiatan($query, $kegiatan_id, $kelompok_id)
    // {
    //     return $query
    //         ->where('kegiatan_id', $kegiatan_id)
    //         ->where('kelompok_id', $kelompok_id)
    //         ->where('siap_hadir', 1);
    // }

    // public function scopeAbsensiKegiatanKelompok($query, $kegiatan_id, $kelompok_id, $status_kehadiran)
    // {
    //     return $query
    //         ->where('kegiatan_id', $kegiatan_id)
    //         ->where('kelompok_id', $kelompok_id)
    //         ->where('status_kehadiran', $status_kehadiran);
    // }

    // public function scopeAbsensiKegiatanUser($query, $kegiatan_id, $kelompok_id, $status_kehadiran)
    // {
    //     return $query
    //         ->where('kegiatan_id', $kegiatan_id)
    //         ->where('kelompok_id', $kelompok_id)
    //         ->where('status_kehadiran', $status_kehadiran);
    // }

    // public function scopeUserTerdaftarKegiatan($query, $kegiatan_id, $kelompok_id)
    // {
    //     return $query
    //         ->where('kegiatan_id', $kegiatan_id)
    //         ->where('kelompok_id', $kelompok_id)
    //         ->where('siap_hadir', 1);
    // }
}

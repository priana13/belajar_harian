<?php

namespace App\Models;

use App\Models\AbsensiKegiatan;
use App\Models\KategoriKegiatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kegiatan extends Model
{
    use HasFactory;
    protected $table = "kegiatan";
    protected $guarded = ['id'];

    public function kategori_kegiatan()
    {
        return $this->belongsTo(KategoriKegiatan::class, 'kategori_id', 'id');
    }

    public function absensi_kegiatan()
    {
        return $this->hasMany(AbsensiKegiatan::class, 'kegiatan_id');
    }

    public function kegiatan_lampau()
    {
        return $this->where('tgl', '<', date('Y-m-d'));
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('waktu', $tahun);
    }
}

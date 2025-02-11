<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKegiatan extends Model
{
    use HasFactory;
    protected $table = "kategori_kegiatan";
    protected $guarded = [];

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'kategori_id');
    }

    public function absensi_kegiatan()
    {
        return $this->hasManyThrough(
            AbsensiKegiatan::class,
            Kegiatan::class,
            'kategori_id',
            'kegiatan_id'
        );
    }
}

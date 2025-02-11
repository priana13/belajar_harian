<?php

namespace App\Models;

use App\Models\Materi;
use App\Models\SoalUjian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soal';
    protected $guarded = [];

    public function jenis_ujian()
    {
        return $this->belongsTo(JenisUjian::class , 'jenis_ujian_id');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function pertemuan()
    {
        return $this->belongsTo(MateriDetail::class, 'materi_detail_id');
    }

    public function soal_ujian()
    {
        return $this->hasMany(SoalUjian::class);
    }

    public function jadwal()
    {
        return $this->belongsToMany(JadwalUjian::class, 'jadwal_ujian_soal', 'soal_id', 'jadwal_ujian_id');
    }

    public function scopeHarian($query){
        
        return $query->where('jenis_ujian_id', 1);
    }

    public function scopePekanan($query){
        
        return $query->where('jenis_ujian_id', 2);
    }

    public function scopeAkhir($query){
        
        return $query->where('jenis_ujian_id', 3);
    }

    public function scopeUrutan($query , $urutan){

        return $query->where('urutan', $urutan);
    }

}

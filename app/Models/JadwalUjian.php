<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalUjian extends Model
{
    use HasFactory;
    protected $table = 'jadwal_ujian';

    protected $guarded = [];

    public function soal(){
        return $this->belongsToMany(Soal::class, 'jadwal_ujian_soal', 'jadwal_ujian_id', 'soal_id');
    }
    
    public function angkatan(){
        return $this->belongsTo(Angkatan::class,'angkatan_id', 'id');
    }

    public function soal_ujian(){

        return $this->hasMany(JadwalUjianSoal::class);
    }

    public function roadmap(){

        return $this->belongsTo(Roadmap::class, 'roadmap_id');
    }

    public function gelombang(){

        return $this->belongsTo(Gelombang::class, 'gelombang_id');
    }

    public function materi(){
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    public function scopeHarian($query){

        return $query->where('type', 'Harian');
    }

    public function scopePekanan($query){

        return $query->where('type', 'Pekanan');
    }

    public function scopeAkhir($query){

        return $query->where('type', 'Akhir');
    }

    public function scopeTanggal($query , $tanggal){
      
        return $query->whereDate('tanggal', $tanggal);
    }

    public function scopeHariIni($query){
      
        return $query->whereDate('tanggal', now());
    }

    public function scopeDuaHari($query){

        $kemarin = now()->yesterday();      

        return $query->whereDate('tanggal', now())->orWhere('tanggal', $kemarin );
    }

}

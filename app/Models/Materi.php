<?php

namespace App\Models;

use App\Models\Soal;
use App\Models\Ujian;
use App\Models\MateriDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materi extends Model
{
    use HasFactory;

    protected $table = "materi";
    protected $guarded = [];

    public function soal()
    {
        return $this->hasMany(Soal::class);
    }

    public function materi_detail(){
        return $this->hasMany(MateriDetail::class);
    }

    public function pertemuan(){
        return $this->hasMany(MateriDetail::class);
    }

    public function ujian(){
        return $this->hasMany(Ujian::class);
    }

    public function kategori(){

        return $this->belongsTo(KategoriMateri::class, 'kategori_id');
    }
    
    public function angkatan(){

        return $this->hasMany(Angkatan::class);
    }

    public function sertifikat(){

        return $this->belongsTo(Sertifikat::class, 'sertifikat_id');
    }

    public function scopeReady($query){

        return $query->where('is_active', true)->whereHas('pertemuan')->whereHas('soal');
    }

    public function roadmaps()
    {
        return $this->belongsToMany(Roadmap::class, 'roadmap_materi', 'materi_id', 'roadmap_id');
    }
}

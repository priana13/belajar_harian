<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;
    protected $table = 'ujian';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function jenis_ujian()
    {
        return $this->belongsTo(JenisUjian::class);
    }

    public function soal_ujian(){

        return $this->hasMany(SoalUjian::class, 'ujian_id');
    }

    public function scopeStatusUjian($query, $materi_id, $user_id)
    {
        return $this->where('materi_id', $materi_id)
            ->where('user_id', $user_id);
    }

    public function scopeHarian($query){

        return $query->where('jenis_ujian_id', 1);
    }

    public function scopePekanan($query){

        return $query->where('jenis_ujian_id', 2);
    }

    public function scopeUjianAkhir($query){

        return $query->where('jenis_ujian_id', 3);
    }

    public function scopeLulus($query){

        return $query->where('keterangan', 'Lulus');
    }

    public function scopeTidakLulus($query){

        return $query->where('keterangan', 'Tidak Lulus');
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalUjianSoal extends Model
{
    use HasFactory;
    protected $table = 'jadwal_ujian_soal';

    protected $guarded = [];

    public function soal(){

        return $this->belongsTo(Soal::class);
    }
}

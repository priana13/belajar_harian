<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AngkatanUser extends Model
{
    use HasFactory;

    protected $table = 'angkatan_users';

    protected $guarded = [];

    public function user(){

        return $this->belongsTo(User::class, 'user_id');
    }

    public function angkatan(){

        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }

    public function kelas(){

        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function scopeAktif($query){

        return $query->where('status', 'Aktif');
    }

    public function scopeLulus($query){

        return $query->where('keterangan', "Lulus");
    }

    public function scopeTidakLulus($query){

        return $query->where('keterangan', "Tidak Lulus");
    }

}

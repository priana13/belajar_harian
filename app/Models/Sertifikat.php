<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function angkatan(){

        return $this->hasMany(Angkatan::class,'sertifikat_id');
    }

    public function materi(){

        return $this->hasMany(Materi::class,'sertifikat_id');
    }
}

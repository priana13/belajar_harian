<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarNilai extends Model
{
    use HasFactory;

    protected $table = 'daftar_nilai';

    protected $guarded = [];

    public function user(){

        return $this->belongsTo(User::class);
    }

    public function materi(){

        return $this->belongsTo(Materi::class);
    }

}

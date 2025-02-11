<?php

namespace App\Models;

use App\Models\Materi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriMateri extends Model
{
    use HasFactory;
    protected $table = "kategori_materi";
    protected $guarded = [];

    public function materi(){

        return $this->hasMany(Materi::class, 'kategori_id', 'id');
    }
}

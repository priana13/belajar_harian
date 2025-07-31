<?php

namespace App\Models;

use App\Models\Materi;
use App\Models\MateriDetailUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MateriDetail extends Model
{
    use HasFactory;
    protected $table = "materi_detail";
    protected $guarded = [];

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function jadwal_belajar()
    {
        return $this->hasMany(Belajar::class);
    }


    public function materi_detail_user()
    {
        return $this->hasMany(MateriDetailUser::class, 'materi_detail_id');
    }

    public function images()
    {
        return $this->hasMany(MateriDetailImage::class, 'materi_detail_id');
    }
}

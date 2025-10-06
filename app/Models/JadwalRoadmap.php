<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalRoadmap extends Model
{
    use HasFactory;

    protected $table = 'jadwal_roadmap';

    protected $guarded = [];


    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class, 'gelombang_id');
    }

    public function roadmap()
    {
        return $this->belongsTo(Roadmap::class, 'roadmap_id');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }


}

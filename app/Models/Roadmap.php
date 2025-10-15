<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roadmap extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_roadmap',
        'detail'
    ];

    /**
     * Relasi Many-to-Many dengan Materi
     */
    public function materi()
    {
        return $this->belongsToMany(Materi::class, 'roadmap_materi', 'roadmap_id', 'materi_id');
    }

    /**
     * Relasi Many-to-Many dengan Gelombang
     */
    public function gelombangs()
    {
        return $this->belongsToMany(Gelombang::class, 'gelombang_roadmap', 'roadmap_id', 'gelombang_id');
    }

    /**
     * Relasi Many-to-Many dengan User
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roadmap', 'roadmap_id', 'user_id');
    }

    /**
     * Relasi One-to-Many dengan JadwalRoadmap
     */
    public function jadwalRoadmaps()
    {
        return $this->hasMany(JadwalRoadmap::class, 'roadmap_id');
    }
    
}
